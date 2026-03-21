package com.deepanshu.dkassistant.service

import android.app.Notification
import android.app.RemoteInput
import android.content.Intent
import android.os.Build
import android.os.Bundle
import android.service.notification.NotificationListenerService
import android.service.notification.StatusBarNotification
import android.speech.tts.TextToSpeech
import androidx.core.app.NotificationCompat
import kotlinx.coroutines.*
import java.util.*

/**
 * Notification Reader Service
 *
 * This NotificationListenerService intercepts incoming notifications and:
 * - Reads WhatsApp messages aloud using TTS
 * - Provides voice reply capability using RemoteInput API
 * - Filters and manages notification priorities
 *
 * Required Permission: BIND_NOTIFICATION_LISTENER_SERVICE
 *
 * @author Deepanshu (Boss)
 */
class NotificationReaderService : NotificationListenerService(), TextToSpeech.OnInitListener {

    companion object {
        private const val WHATSAPP_PACKAGE = "com.whatsapp"
        private const val WHATSAPP_BUSINESS_PACKAGE = "com.whatsapp.w4b"
        private const val INSTAGRAM_PACKAGE = "com.instagram.android"
        private const val TELEGRAM_PACKAGE = "org.telegram.messenger"
        private const val ACTION_REPLY = "com.deepanshu.dkassistant.REPLY"
    }

    private lateinit var tts: TextToSpeech
    private var isTtsReady = false
    private val serviceScope = CoroutineScope(Dispatchers.Main + SupervisorJob())
    private val processedNotifications = mutableSetOf<String>()

    override fun onCreate() {
        super.onCreate()
        tts = TextToSpeech(this, this)
    }

    override fun onInit(status: Int) {
        if (status == TextToSpeech.SUCCESS) {
            val result = tts.setLanguage(Locale.US)
            isTtsReady = result != TextToSpeech.LANG_MISSING_DATA &&
                        result != TextToSpeech.LANG_NOT_SUPPORTED

            // Configure TTS
            tts.setPitch(1.0f)
            tts.setSpeechRate(1.0f)
        }
    }

    override fun onListenerConnected() {
        super.onListenerConnected()
        // Service is now ready to receive notifications
    }

    override fun onNotificationPosted(sbn: StatusBarNotification?) {
        super.onNotificationPosted(sbn)

        if (sbn == null) return

        val packageName = sbn.packageName
        val notification = sbn.notification ?: return

        // Create unique ID for this notification
        val notificationId = "${sbn.key}_${sbn.postTime}"

        // Skip if already processed
        if (processedNotifications.contains(notificationId)) {
            return
        }

        // Process only supported app notifications
        when (packageName) {
            WHATSAPP_PACKAGE,
            WHATSAPP_BUSINESS_PACKAGE -> {
                handleWhatsAppNotification(notification, notificationId)
            }
            INSTAGRAM_PACKAGE -> {
                handleInstagramNotification(notification, notificationId)
            }
            TELEGRAM_PACKAGE -> {
                handleTelegramNotification(notification, notificationId)
            }
        }
    }

    private fun handleWhatsAppNotification(notification: Notification, notificationId: String) {
        val extras = notification.extras ?: return

        // Extract message details
        val title = extras.getCharSequence(Notification.EXTRA_TITLE)?.toString()
        val text = extras.getCharSequence(Notification.EXTRA_TEXT)?.toString()
        val bigText = extras.getCharSequence(Notification.EXTRA_BIG_TEXT)?.toString()

        // Use bigText if available, otherwise use text
        val messageBody = bigText ?: text

        if (title != null && messageBody != null) {
            // Mark as processed
            processedNotifications.add(notificationId)

            // Clean up old entries (keep last 50)
            if (processedNotifications.size > 50) {
                val iterator = processedNotifications.iterator()
                repeat(10) {
                    if (iterator.hasNext()) {
                        iterator.next()
                        iterator.remove()
                    }
                }
            }

            // Read the message aloud
            readMessageAloud(title, messageBody, "WhatsApp")

            // Extract RemoteInput for quick reply
            val remoteInputs = getRemoteInputs(notification)
            if (remoteInputs.isNotEmpty()) {
                // Store for voice reply capability
                storeReplyAction(notificationId, notification, remoteInputs[0])
            }
        }
    }

    private fun handleInstagramNotification(notification: Notification, notificationId: String) {
        val extras = notification.extras ?: return

        val title = extras.getCharSequence(Notification.EXTRA_TITLE)?.toString()
        val text = extras.getCharSequence(Notification.EXTRA_TEXT)?.toString()

        if (title != null && text != null && !text.contains("liked") && !text.contains("started following")) {
            processedNotifications.add(notificationId)
            readMessageAloud(title, text, "Instagram")
        }
    }

    private fun handleTelegramNotification(notification: Notification, notificationId: String) {
        val extras = notification.extras ?: return

        val title = extras.getCharSequence(Notification.EXTRA_TITLE)?.toString()
        val text = extras.getCharSequence(Notification.EXTRA_TEXT)?.toString()

        if (title != null && text != null) {
            processedNotifications.add(notificationId)
            readMessageAloud(title, text, "Telegram")
        }
    }

    private fun readMessageAloud(sender: String, message: String, appName: String) {
        if (!isTtsReady) return

        // Format the announcement
        val announcement = "Boss, you have a new message on $appName from $sender. " +
                          "The message says: $message"

        // Speak the notification
        serviceScope.launch {
            delay(500) // Small delay to avoid overlapping with other sounds
            speak(announcement)
        }
    }

    private fun speak(text: String) {
        if (isTtsReady) {
            tts.speak(text, TextToSpeech.QUEUE_ADD, null, null)
        }
    }

    private fun getRemoteInputs(notification: Notification): List<RemoteInput> {
        val remoteInputs = mutableListOf<RemoteInput>()

        notification.actions?.forEach { action ->
            action.remoteInputs?.forEach { remoteInput ->
                remoteInputs.add(remoteInput)
            }
        }

        return remoteInputs
    }

    private fun storeReplyAction(
        notificationId: String,
        notification: Notification,
        remoteInput: RemoteInput
    ) {
        // Find the reply action
        val replyAction = notification.actions?.firstOrNull { action ->
            action.remoteInputs?.contains(remoteInput) == true
        }

        if (replyAction != null) {
            // Store in shared preferences for later use
            val prefs = getSharedPreferences("dk_notifications", MODE_PRIVATE)
            prefs.edit().apply {
                putString("${notificationId}_key", remoteInput.resultKey)
                putString("${notificationId}_action", replyAction.title.toString())
                apply()
            }
        }
    }

    /**
     * Send a voice reply to a notification using RemoteInput
     *
     * @param notificationId The ID of the notification to reply to
     * @param replyText The text to send as reply
     */
    fun sendVoiceReply(notificationId: String, replyText: String) {
        val prefs = getSharedPreferences("dk_notifications", MODE_PRIVATE)
        val resultKey = prefs.getString("${notificationId}_key", null) ?: return

        try {
            // Get all active notifications
            val activeNotifications = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                activeNotifications
            } else {
                emptyArray()
            }

            // Find matching notification
            val sbn = activeNotifications.firstOrNull { it.key == notificationId }
            val notification = sbn?.notification ?: return

            // Find reply action
            val replyAction = notification.actions?.firstOrNull { action ->
                action.remoteInputs?.any { it.resultKey == resultKey } == true
            } ?: return

            // Create reply intent
            val replyIntent = Intent()
            val bundle = Bundle()
            bundle.putCharSequence(resultKey, replyText)

            RemoteInput.addResultsToIntent(
                replyAction.remoteInputs,
                replyIntent,
                bundle
            )

            // Send the reply
            replyAction.actionIntent.send(this, 0, replyIntent)

            speak("Boss, I've sent your reply.")
        } catch (e: Exception) {
            speak("Sorry Boss, I couldn't send the reply.")
            e.printStackTrace()
        }
    }

    override fun onNotificationRemoved(sbn: StatusBarNotification?) {
        super.onNotificationRemoved(sbn)
        // Notification was dismissed or removed
    }

    override fun onListenerDisconnected() {
        super.onListenerDisconnected()
        // Request rebind
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.N) {
            requestRebind(android.content.ComponentName(this, NotificationReaderService::class.java))
        }
    }

    override fun onDestroy() {
        super.onDestroy()

        // Cleanup
        tts.stop()
        tts.shutdown()
        serviceScope.cancel()
        processedNotifications.clear()
    }
}
