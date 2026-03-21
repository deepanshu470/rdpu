package com.deepanshu.dkassistant.manager

import android.content.Context
import android.content.SharedPreferences
import org.json.JSONArray
import org.json.JSONObject

/**
 * Offline AI Brain Manager
 *
 * Provides offline AI capabilities without requiring external API:
 * - Pre-trained responses for common queries
 * - Pattern matching for commands
 * - Memory storage for user information
 * - Bilingual support (Hindi + English)
 * - Manual training capability
 *
 * Works completely offline - no internet needed
 *
 * @author Deepanshu (Boss)
 */
class OfflineAIBrainManager(private val context: Context) {

    companion object {
        private const val PREFS_NAME = "dk_offline_ai"
        private const val KEY_TRAINED_RESPONSES = "trained_responses"
        private const val KEY_MEMORY_DATA = "memory_data"
        private const val KEY_LANGUAGE = "language"
    }

    private val prefs: SharedPreferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
    private val memoryStorage = mutableMapOf<String, String>()
    private val trainedResponses = mutableMapOf<String, String>()

    init {
        loadTrainedResponses()
        loadMemoryData()
        initializeDefaultResponses()
    }

    /**
     * Get offline AI response for query
     */
    fun getOfflineResponse(query: String): String {
        val lowerQuery = query.lowercase().trim()

        // Check for memory recall requests
        if (lowerQuery.contains("remember") || lowerQuery.contains("yaad")) {
            return handleMemoryRecall(query)
        }

        // Check for save/store requests
        if (lowerQuery.contains("save") || lowerQuery.contains("store") || lowerQuery.contains("yaad rakh")) {
            return "Yes Boss, I'm listening. What should I remember?"
        }

        // Check trained responses first (pattern matching)
        trainedResponses.forEach { (pattern, response) ->
            if (lowerQuery.contains(pattern.lowercase())) {
                return response
            }
        }

        // Default responses based on common patterns
        return when {
            // Greetings (English)
            lowerQuery.contains("hello") || lowerQuery.contains("hi") ->
                "Hello Boss, how can I assist you today?"

            // Greetings (Hindi)
            lowerQuery.contains("namaste") || lowerQuery.contains("namaskar") ->
                "Namaste Boss, aapki kya seva kar sakta hoon?"

            // Time queries
            lowerQuery.contains("time") || lowerQuery.contains("samay") ->
                "The current time is ${getCurrentTime()}, Boss."

            // Date queries
            lowerQuery.contains("date") || lowerQuery.contains("tarikh") ->
                "Today's date is ${getCurrentDate()}, Boss."

            // Who are you (English)
            lowerQuery.contains("who are you") || lowerQuery.contains("your name") ->
                "I am DK, your personal AI assistant. I work exclusively for you, Boss Deepanshu."

            // Who are you (Hindi)
            lowerQuery.contains("tum kaun") || lowerQuery.contains("aap kaun") ->
                "Main DK hoon, aapka personal AI assistant. Main sirf aapke liye kaam karta hoon, Boss Deepanshu."

            // Thank you (English)
            lowerQuery.contains("thank") ->
                "You're welcome, Boss. Happy to help!"

            // Thank you (Hindi)
            lowerQuery.contains("dhanyavaad") || lowerQuery.contains("shukriya") ->
                "Aapka swagat hai, Boss. Mujhe khushi hai ki main madad kar saka!"

            // Weather (offline - cannot fetch real weather)
            lowerQuery.contains("weather") || lowerQuery.contains("mausam") ->
                "Boss, I need internet connection to check the weather. Currently working in offline mode."

            // How are you (English)
            lowerQuery.contains("how are you") ->
                "I'm functioning perfectly, Boss. Ready to assist you!"

            // How are you (Hindi)
            lowerQuery.contains("kaise ho") || lowerQuery.contains("kaisa hai") ->
                "Main bilkul theek hoon, Boss. Aapki seva ke liye taiyaar hoon!"

            // Help (English)
            lowerQuery.contains("help") || lowerQuery.contains("what can you do") ->
                "I can: open apps, control flashlight, answer calls, read notifications, remember information, and chat with you in Hindi or English, Boss!"

            // Help (Hindi)
            lowerQuery.contains("madad") || lowerQuery.contains("kya kar sakte") ->
                "Main: apps khol sakta hoon, flashlight control kar sakta hoon, calls answer kar sakta hoon, notifications padh sakta hoon, information yaad rakh sakta hoon, aur Hindi ya English mein baat kar sakta hoon, Boss!"

            // Good morning/night (English)
            lowerQuery.contains("good morning") ->
                "Good morning, Boss! Have a wonderful day ahead!"
            lowerQuery.contains("good night") ->
                "Good night, Boss! Sleep well and have pleasant dreams!"

            // Good morning/night (Hindi)
            lowerQuery.contains("shubh prabhat") || lowerQuery.contains("suprabhat") ->
                "Shubh prabhat, Boss! Aapka din shubh ho!"
            lowerQuery.contains("shubh ratri") || lowerQuery.contains("good night") ->
                "Shubh ratri, Boss! Aaram se soiye!"

            // Yes/No responses (English)
            lowerQuery == "yes" || lowerQuery == "ok" || lowerQuery == "okay" ->
                "Understood, Boss!"
            lowerQuery == "no" || lowerQuery == "nope" ->
                "Alright, Boss!"

            // Yes/No responses (Hindi)
            lowerQuery == "haan" || lowerQuery == "ji" || lowerQuery == "thik hai" ->
                "Samajh gaya, Boss!"
            lowerQuery == "nahi" || lowerQuery == "na" ->
                "Theek hai, Boss!"

            // Compliments
            lowerQuery.contains("good job") || lowerQuery.contains("well done") || lowerQuery.contains("badiya") ->
                "Thank you, Boss! I'm always here to serve you!"

            // Default fallback
            else -> "I understand, Boss. How can I help you with that?"
        }
    }

    /**
     * Store information in memory
     */
    fun storeMemory(key: String, value: String) {
        memoryStorage[key.lowercase()] = value
        saveMemoryData()
    }

    /**
     * Recall information from memory
     */
    private fun handleMemoryRecall(query: String): String {
        val lowerQuery = query.lowercase()

        // Try to find matching memory key
        memoryStorage.forEach { (key, value) ->
            if (lowerQuery.contains(key)) {
                return "Boss, I remember: $value"
            }
        }

        return "Boss, I don't have that information stored in my memory. Would you like me to remember something?"
    }

    /**
     * Train with custom response
     */
    fun trainResponse(pattern: String, response: String) {
        trainedResponses[pattern.lowercase()] = response
        saveTrainedResponses()
    }

    /**
     * Get all stored memories
     */
    fun getAllMemories(): Map<String, String> {
        return memoryStorage.toMap()
    }

    /**
     * Clear specific memory
     */
    fun clearMemory(key: String) {
        memoryStorage.remove(key.lowercase())
        saveMemoryData()
    }

    /**
     * Clear all memories
     */
    fun clearAllMemories() {
        memoryStorage.clear()
        saveMemoryData()
    }

    /**
     * Get all trained responses
     */
    fun getAllTrainedResponses(): Map<String, String> {
        return trainedResponses.toMap()
    }

    /**
     * Clear trained responses
     */
    fun clearTrainedResponses() {
        trainedResponses.clear()
        saveTrainedResponses()
    }

    private fun getCurrentTime(): String {
        val calendar = java.util.Calendar.getInstance()
        val hour = calendar.get(java.util.Calendar.HOUR_OF_DAY)
        val minute = calendar.get(java.util.Calendar.MINUTE)
        return String.format("%02d:%02d", hour, minute)
    }

    private fun getCurrentDate(): String {
        val calendar = java.util.Calendar.getInstance()
        val day = calendar.get(java.util.Calendar.DAY_OF_MONTH)
        val month = calendar.get(java.util.Calendar.MONTH) + 1
        val year = calendar.get(java.util.Calendar.YEAR)
        return "$day/$month/$year"
    }

    private fun initializeDefaultResponses() {
        // Add some default trained responses if not already present
        if (trainedResponses.isEmpty()) {
            trainedResponses["deepanshu"] = "Yes Boss, that's you! The owner and master I serve."
            trainedResponses["jarvis"] = "I'm inspired by Jarvis, but I'm DK - your personal assistant, Boss!"
            trainedResponses["offline"] = "Yes Boss, I can work completely offline without any internet or API!"

            // Hindi defaults
            trainedResponses["boss"] = "Ji Boss, main hazir hoon!"
            trainedResponses["kaam karo"] = "Ji Boss, bataiye kya kaam hai?"
            trainedResponses["chalo"] = "Ji Boss, chaliye!"

            saveTrainedResponses()
        }
    }

    private fun saveTrainedResponses() {
        val jsonObject = JSONObject()
        trainedResponses.forEach { (key, value) ->
            jsonObject.put(key, value)
        }
        prefs.edit().putString(KEY_TRAINED_RESPONSES, jsonObject.toString()).apply()
    }

    private fun loadTrainedResponses() {
        val jsonString = prefs.getString(KEY_TRAINED_RESPONSES, null)
        if (jsonString != null) {
            try {
                val jsonObject = JSONObject(jsonString)
                jsonObject.keys().forEach { key ->
                    trainedResponses[key] = jsonObject.getString(key)
                }
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }
    }

    private fun saveMemoryData() {
        val jsonObject = JSONObject()
        memoryStorage.forEach { (key, value) ->
            jsonObject.put(key, value)
        }
        prefs.edit().putString(KEY_MEMORY_DATA, jsonObject.toString()).apply()
    }

    private fun loadMemoryData() {
        val jsonString = prefs.getString(KEY_MEMORY_DATA, null)
        if (jsonString != null) {
            try {
                val jsonObject = JSONObject(jsonString)
                jsonObject.keys().forEach { key ->
                    memoryStorage[key] = jsonObject.getString(key)
                }
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }
    }

    /**
     * Set preferred language
     */
    fun setLanguage(language: String) {
        prefs.edit().putString(KEY_LANGUAGE, language).apply()
    }

    /**
     * Get preferred language
     */
    fun getLanguage(): String {
        return prefs.getString(KEY_LANGUAGE, "both") ?: "both"
    }

    /**
     * Check if offline mode is being used
     */
    fun isOfflineMode(): Boolean {
        return true // Always available offline
    }
}
