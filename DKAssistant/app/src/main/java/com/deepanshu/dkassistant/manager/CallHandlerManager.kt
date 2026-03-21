package com.deepanshu.dkassistant.manager

import android.content.Context
import android.media.AudioManager
import android.os.Build
import android.speech.tts.TextToSpeech
import android.telecom.TelecomManager
import android.telephony.TelephonyManager
import androidx.annotation.RequiresApi
import java.util.*

/**
 * Call Handler Manager
 *
 * Manages incoming calls:
 * - Auto-answer capability
 * - Caller name collection
 * - Introduction as Deepanshu's assistant
 * - Call transfer to Boss
 * - Message taking
 * - Bilingual support (Hindi/English)
 *
 * @author Deepanshu (Boss)
 */
class CallHandlerManager(private val context: Context) {

    companion object {
        private const val TAG = "CallHandlerManager"
        private const val PREFS_NAME = "dk_call_prefs"
        private const val KEY_AUTO_ANSWER = "auto_answer"
    }

    private val prefs = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
    private val audioManager = context.getSystemService(Context.AUDIO_SERVICE) as AudioManager
    private var tts: TextToSpeech? = null
    private var callerState = CallState.INITIAL

    enum class CallState {
        INITIAL,
        ASKED_NAME,
        GOT_NAME,
        ASKED_PURPOSE,
        TRANSFERRING,
        ENDED
    }

    /**
     * Initialize TTS for call conversations
     */
    fun initializeTTS(onReady: () -> Unit) {
        tts = TextToSpeech(context) { status ->
            if (status == TextToSpeech.SUCCESS) {
                tts?.language = Locale("hi", "IN") // Support Hindi
                onReady()
            }
        }
    }

    /**
     * Handle incoming call - first greeting
     */
    fun handleIncomingCall(callerNumber: String?) {
        callerState = CallState.INITIAL

        // Greet caller (bilingual)
        val greeting = """
            Hello! Namaste!
            This is DK, Deepanshu Sir's personal assistant.
            I'm here to help you.
            May I know your name please?
            Kripya apna naam bataiye?
        """.trimIndent()

        speak(greeting)
        callerState = CallState.ASKED_NAME
    }

    /**
     * Process caller's response
     */
    fun processCallerResponse(response: String) {
        val lowerResponse = response.lowercase()

        when (callerState) {
            CallState.ASKED_NAME -> {
                // Got the name
                val callerName = extractName(response)
                handleCallerName(callerName)
            }

            CallState.ASKED_PURPOSE -> {
                // Check if caller wants to speak to Boss
                if (lowerResponse.contains("speak") ||
                    lowerResponse.contains("talk") ||
                    lowerResponse.contains("bat karn") ||
                    lowerResponse.contains("baat karni") ||
                    lowerResponse.contains("boss")) {

                    transferToBoss()
                } else {
                    // Take message
                    takeMessage(response)
                }
            }

            else -> {
                // Handle other states
            }
        }
    }

    private fun handleCallerName(name: String) {
        val response = """
            Thank you, $name!
            How can I help you today?
            Would you like to speak with Deepanshu Sir?
            Aap Deepanshu Sir se baat karna chahenge?
        """.trimIndent()

        speak(response)
        callerState = CallState.ASKED_PURPOSE
    }

    private fun transferToBoss() {
        val message = """
            Sure!
            Please hold on, I am transferring your call to Deepanshu Sir.
            Kripya pratiksha karein, main aapko Deepanshu Sir se jod rahi hoon.
        """.trimIndent()

        speak(message)
        callerState = CallState.TRANSFERRING

        // Notify Boss about incoming call
        notifyBossAboutCall()
    }

    private fun takeMessage(message: String) {
        val response = """
            I understand.
            I will pass your message to Deepanshu Sir.
            He will get back to you soon.
            Main aapka sandesh Deepanshu Sir ko de dungi.
            Woh jald hi aapse sampark karenge.
        """.trimIndent()

        speak(response)

        // Save message for Boss
        saveMessageForBoss(message)
        endCall()
    }

    private fun extractName(response: String): String {
        // Simple name extraction - can be enhanced
        val words = response.split(" ")
        return words.firstOrNull()?.capitalize() ?: "Sir/Madam"
    }

    private fun speak(text: String) {
        tts?.speak(text, TextToSpeech.QUEUE_ADD, null, null)
    }

    private fun notifyBossAboutCall() {
        // This would vibrate or alert the Boss
        // Implementation depends on requirements
    }

    private fun saveMessageForBoss(message: String) {
        val timestamp = System.currentTimeMillis()
        val messages = prefs.getStringSet("pending_messages", mutableSetOf()) ?: mutableSetOf()
        messages.add("$timestamp:$message")
        prefs.edit().putStringSet("pending_messages", messages).apply()
    }

    private fun endCall() {
        val goodbye = """
            Thank you for calling!
            Have a great day!
            Dhanyavaad! Aapka din shubh ho!
        """.trimIndent()

        speak(goodbye)
        callerState = CallState.ENDED
    }

    /**
     * Get pending messages for Boss
     */
    fun getPendingMessages(): List<String> {
        val messages = prefs.getStringSet("pending_messages", emptySet()) ?: emptySet()
        return messages.map { it.substringAfter(":") }
    }

    /**
     * Clear pending messages
     */
    fun clearPendingMessages() {
        prefs.edit().remove("pending_messages").apply()
    }

    /**
     * Set auto-answer calls
     */
    fun setAutoAnswer(enabled: Boolean) {
        prefs.edit().putBoolean(KEY_AUTO_ANSWER, enabled).apply()
    }

    /**
     * Check if auto-answer is enabled
     */
    fun isAutoAnswerEnabled(): Boolean {
        return prefs.getBoolean(KEY_AUTO_ANSWER, false)
    }

    /**
     * Cleanup
     */
    fun cleanup() {
        tts?.stop()
        tts?.shutdown()
    }
}
