package com.dk.assistant.ai

import android.app.Service
import android.content.Intent
import android.os.IBinder
import com.dk.assistant.BuildConfig
import com.dk.assistant.DKApplication
import com.dk.assistant.util.DKLogger
import kotlinx.coroutines.*
import okhttp3.*
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.RequestBody.Companion.toRequestBody
import org.json.JSONArray
import org.json.JSONObject
import java.io.IOException

class AIBrainService : Service() {

    companion object {
        const val ACTION_QUERY   = "com.dk.assistant.AI_QUERY"
        const val ACTION_RESPOND = "com.dk.assistant.AI_RESPOND"
        const val EXTRA_QUERY    = "query"
        const val EXTRA_RESPONSE = "response"
    }

    private val scope = CoroutineScope(SupervisorJob() + Dispatchers.IO)
    private val client = OkHttpClient()

    override fun onBind(intent: Intent?): IBinder? = null

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        if (intent?.action == ACTION_QUERY) {
            val query = intent.getStringExtra(EXTRA_QUERY) ?: return START_NOT_STICKY
            scope.launch {
                val response = ask(query)
                val broadcast = Intent(ACTION_RESPOND).apply {
                    putExtra(EXTRA_RESPONSE, response)
                    setPackage(packageName)
                }
                sendBroadcast(broadcast)
            }
        }
        return START_NOT_STICKY
    }

    override fun onDestroy() { scope.cancel(); super.onDestroy() }

    /** Try Gemini first, fall back to OpenAI if key present */
    suspend fun ask(query: String): String = withContext(Dispatchers.IO) {
        val memory = (application as DKApplication).memoryManager.getRecentContext(10)
        val systemPrompt = """You are DK, a highly intelligent personal AI assistant for Boss Deepanshu.
You are loyal, helpful, concise, and always address the user as "Boss".
You have context of previous conversations. Answer accurately.
Recent context:
$memory"""

        val geminiKey = BuildConfig.GEMINI_API_KEY
        if (geminiKey.isNotBlank()) {
            return@withContext askGemini(query, systemPrompt, geminiKey)
        }
        val openaiKey = BuildConfig.OPENAI_API_KEY
        if (openaiKey.isNotBlank()) {
            return@withContext askOpenAI(query, systemPrompt, openaiKey)
        }
        return@withContext "I'm offline, Boss. Please configure an API key in settings."
    }

    private fun askGemini(query: String, system: String, apiKey: String): String {
        val url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey"
        val body = JSONObject().apply {
            put("contents", JSONArray().apply {
                put(JSONObject().apply {
                    put("role", "user")
                    put("parts", JSONArray().apply {
                        put(JSONObject().put("text", "$system\n\nUser: $query"))
                    })
                })
            })
        }.toString()

        val request = Request.Builder()
            .url(url)
            .post(body.toRequestBody("application/json".toMediaType()))
            .build()

        return try {
            client.newCall(request).execute().use { resp ->
                val json = JSONObject(resp.body?.string() ?: return "Empty response from Gemini.")
                json.getJSONArray("candidates")
                    .getJSONObject(0)
                    .getJSONObject("content")
                    .getJSONArray("parts")
                    .getJSONObject(0)
                    .getString("text")
            }
        } catch (e: Exception) {
            DKLogger.e("Gemini error", e)
            "Sorry Boss, I couldn't reach Gemini right now."
        }
    }

    private fun askOpenAI(query: String, system: String, apiKey: String): String {
        val url = "https://api.openai.com/v1/chat/completions"
        val body = JSONObject().apply {
            put("model", "gpt-4o-mini")
            put("messages", JSONArray().apply {
                put(JSONObject().apply { put("role", "system"); put("content", system) })
                put(JSONObject().apply { put("role", "user");   put("content", query) })
            })
            put("max_tokens", 500)
        }.toString()

        val request = Request.Builder()
            .url(url)
            .addHeader("Authorization", "Bearer $apiKey")
            .post(body.toRequestBody("application/json".toMediaType()))
            .build()

        return try {
            client.newCall(request).execute().use { resp ->
                val json = JSONObject(resp.body?.string() ?: return "Empty response.")
                json.getJSONArray("choices")
                    .getJSONObject(0)
                    .getJSONObject("message")
                    .getString("content")
            }
        } catch (e: Exception) {
            DKLogger.e("OpenAI error", e)
            "Sorry Boss, I couldn't reach OpenAI right now."
        }
    }
}
