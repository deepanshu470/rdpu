package com.deepanshu.dkassistant.manager

import android.content.Context
import android.util.Log
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import org.json.JSONArray
import org.json.JSONObject
import java.io.BufferedReader
import java.io.InputStreamReader
import java.io.OutputStreamWriter
import java.net.HttpURLConnection
import java.net.URL

/**
 * AI Brain Manager
 *
 * Integrates with Large Language Model APIs:
 * - OpenAI (GPT-3.5/GPT-4)
 * - Anthropic Claude
 *
 * Provides natural language understanding and conversation capabilities
 * for the DK Assistant to answer questions, provide information, and
 * engage in intelligent conversations with the Boss.
 *
 * @author Deepanshu (Boss)
 */
class AIBrainManager(private val context: Context) {

    companion object {
        private const val TAG = "AIBrainManager"

        // API Configuration
        private const val OPENAI_API_URL = "https://api.openai.com/v1/chat/completions"
        private const val CLAUDE_API_URL = "https://api.anthropic.com/v1/messages"

        private const val PREFS_NAME = "dk_ai_prefs"
        private const val KEY_API_KEY = "api_key"
        private const val KEY_USE_OPENAI = "use_openai"
        private const val KEY_MODEL = "model"

        // Default models
        private const val DEFAULT_OPENAI_MODEL = "gpt-3.5-turbo"
        private const val DEFAULT_CLAUDE_MODEL = "claude-3-5-sonnet-20241022"

        // System prompt for DK Assistant personality
        private const val SYSTEM_PROMPT = """You are DK, a highly advanced personal AI assistant
            |exclusively serving Deepanshu (the Boss). You are intelligent, efficient, loyal, and
            |respectful. Always address him as 'Boss' and provide concise, accurate, and helpful
            |responses. You have a professional yet friendly tone. When asked questions, provide
            |clear and direct answers. Keep responses brief unless detailed explanation is requested.""".trimMargin()
    }

    private val prefs = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
    private val conversationHistory = mutableListOf<Pair<String, String>>() // (role, content)

    /**
     * Get AI response for a user query
     *
     * @param query The user's question or command
     * @return AI-generated response
     */
    suspend fun getAIResponse(query: String): String = withContext(Dispatchers.IO) {
        try {
            val apiKey = getApiKey()
            if (apiKey.isEmpty()) {
                return@withContext "Boss, I need an API key to access my AI brain. Please configure it in settings."
            }

            val useOpenAI = prefs.getBoolean(KEY_USE_OPENAI, true)

            return@withContext if (useOpenAI) {
                getOpenAIResponse(apiKey, query)
            } else {
                getClaudeResponse(apiKey, query)
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error getting AI response", e)
            return@withContext "Sorry Boss, I'm having trouble processing that right now. ${e.message}"
        }
    }

    /**
     * Get response from OpenAI API
     */
    private fun getOpenAIResponse(apiKey: String, query: String): String {
        val url = URL(OPENAI_API_URL)
        val connection = url.openConnection() as HttpURLConnection

        try {
            // Add to conversation history
            conversationHistory.add("user" to query)
            if (conversationHistory.size > 10) {
                // Keep only last 10 exchanges
                conversationHistory.removeAt(0)
                conversationHistory.removeAt(0)
            }

            // Build messages array
            val messages = JSONArray().apply {
                // System message
                put(JSONObject().apply {
                    put("role", "system")
                    put("content", SYSTEM_PROMPT)
                })

                // Conversation history
                conversationHistory.forEach { (role, content) ->
                    put(JSONObject().apply {
                        put("role", role)
                        put("content", content)
                    })
                }
            }

            val requestBody = JSONObject().apply {
                put("model", prefs.getString(KEY_MODEL, DEFAULT_OPENAI_MODEL))
                put("messages", messages)
                put("max_tokens", 150)
                put("temperature", 0.7)
            }

            connection.apply {
                requestMethod = "POST"
                setRequestProperty("Content-Type", "application/json")
                setRequestProperty("Authorization", "Bearer $apiKey")
                doOutput = true
                connectTimeout = 15000
                readTimeout = 15000
            }

            // Send request
            OutputStreamWriter(connection.outputStream).use { writer ->
                writer.write(requestBody.toString())
                writer.flush()
            }

            // Read response
            val responseCode = connection.responseCode
            if (responseCode == HttpURLConnection.HTTP_OK) {
                val response = BufferedReader(InputStreamReader(connection.inputStream)).use { reader ->
                    reader.readText()
                }

                val jsonResponse = JSONObject(response)
                val aiMessage = jsonResponse
                    .getJSONArray("choices")
                    .getJSONObject(0)
                    .getJSONObject("message")
                    .getString("content")
                    .trim()

                // Add AI response to history
                conversationHistory.add("assistant" to aiMessage)

                return aiMessage
            } else {
                val errorStream = connection.errorStream
                val errorResponse = BufferedReader(InputStreamReader(errorStream)).use { it.readText() }
                Log.e(TAG, "OpenAI API error: $errorResponse")
                return "Sorry Boss, I received an error from my AI brain. Please check the API key and try again."
            }
        } finally {
            connection.disconnect()
        }
    }

    /**
     * Get response from Claude API
     */
    private fun getClaudeResponse(apiKey: String, query: String): String {
        val url = URL(CLAUDE_API_URL)
        val connection = url.openConnection() as HttpURLConnection

        try {
            // Add to conversation history
            conversationHistory.add("user" to query)
            if (conversationHistory.size > 10) {
                conversationHistory.removeAt(0)
                conversationHistory.removeAt(0)
            }

            // Build messages array (Claude format)
            val messages = JSONArray().apply {
                conversationHistory.forEach { (role, content) ->
                    put(JSONObject().apply {
                        put("role", if (role == "user") "user" else "assistant")
                        put("content", content)
                    })
                }
            }

            val requestBody = JSONObject().apply {
                put("model", prefs.getString(KEY_MODEL, DEFAULT_CLAUDE_MODEL))
                put("max_tokens", 150)
                put("messages", messages)
                put("system", SYSTEM_PROMPT)
            }

            connection.apply {
                requestMethod = "POST"
                setRequestProperty("Content-Type", "application/json")
                setRequestProperty("x-api-key", apiKey)
                setRequestProperty("anthropic-version", "2023-06-01")
                doOutput = true
                connectTimeout = 15000
                readTimeout = 15000
            }

            // Send request
            OutputStreamWriter(connection.outputStream).use { writer ->
                writer.write(requestBody.toString())
                writer.flush()
            }

            // Read response
            val responseCode = connection.responseCode
            if (responseCode == HttpURLConnection.HTTP_OK) {
                val response = BufferedReader(InputStreamReader(connection.inputStream)).use { reader ->
                    reader.readText()
                }

                val jsonResponse = JSONObject(response)
                val aiMessage = jsonResponse
                    .getJSONArray("content")
                    .getJSONObject(0)
                    .getString("text")
                    .trim()

                // Add AI response to history
                conversationHistory.add("assistant" to aiMessage)

                return aiMessage
            } else {
                val errorStream = connection.errorStream
                val errorResponse = BufferedReader(InputStreamReader(errorStream)).use { it.readText() }
                Log.e(TAG, "Claude API error: $errorResponse")
                return "Sorry Boss, I received an error from my AI brain. Please check the API key and try again."
            }
        } finally {
            connection.disconnect()
        }
    }

    /**
     * Set the API key
     */
    fun setApiKey(apiKey: String) {
        prefs.edit().putString(KEY_API_KEY, apiKey).apply()
    }

    /**
     * Get the stored API key
     */
    private fun getApiKey(): String {
        return prefs.getString(KEY_API_KEY, "") ?: ""
    }

    /**
     * Set whether to use OpenAI (true) or Claude (false)
     */
    fun setUseOpenAI(useOpenAI: Boolean) {
        prefs.edit().putBoolean(KEY_USE_OPENAI, useOpenAI).apply()
    }

    /**
     * Check if OpenAI is being used
     */
    fun isUsingOpenAI(): Boolean {
        return prefs.getBoolean(KEY_USE_OPENAI, true)
    }

    /**
     * Set the model to use
     */
    fun setModel(model: String) {
        prefs.edit().putString(KEY_MODEL, model).apply()
    }

    /**
     * Get current model
     */
    fun getCurrentModel(): String {
        val useOpenAI = isUsingOpenAI()
        val defaultModel = if (useOpenAI) DEFAULT_OPENAI_MODEL else DEFAULT_CLAUDE_MODEL
        return prefs.getString(KEY_MODEL, defaultModel) ?: defaultModel
    }

    /**
     * Clear conversation history
     */
    fun clearHistory() {
        conversationHistory.clear()
    }

    /**
     * Get conversation history for display
     */
    fun getHistory(): List<Pair<String, String>> {
        return conversationHistory.toList()
    }

    /**
     * Check if API is configured
     */
    fun isConfigured(): Boolean {
        return getApiKey().isNotEmpty()
    }

    /**
     * Get AI provider name
     */
    fun getProviderName(): String {
        return if (isUsingOpenAI()) "OpenAI" else "Claude"
    }
}
