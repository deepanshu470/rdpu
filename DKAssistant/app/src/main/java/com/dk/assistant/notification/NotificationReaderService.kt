package com.dk.assistant.notification

import android.app.Notification
import android.content.Intent
import android.os.Bundle
import android.service.notification.NotificationListenerService
import android.service.notification.StatusBarNotification
import android.speech.tts.TextToSpeech
import com.dk.assistant.memory.MemoryManager
import com.dk.assistant.DKApplication
import com.dk.assistant.util.DKLogger
import java.util.Locale

class NotificationReaderService : NotificationListenerService(), TextToSpeech.OnInitListener {

    private var tts: TextToSpeech? = null
    private val PRIORITY_APPS = setOf(
        "com.whatsapp", "com.whatsapp.w4b",
        "com.instagram.android", "com.facebook.katana",
        "com.google.android.gm", "com.microsoft.teams",
        "com.slack", "org.telegram.messenger"
    )

    override fun onCreate() {
        super.onCreate()
        tts = TextToSpeech(this, this)
    }

    override fun onInit(status: Int) {
        if (status == TextToSpeech.SUCCESS) tts?.language = Locale.US
    }

    override fun onNotificationPosted(sbn: StatusBarNotification) {
        val pkg = sbn.packageName ?: return
        if (pkg !in PRIORITY_APPS) return

        val extras: Bundle = sbn.notification.extras
        val title = extras.getCharSequence(Notification.EXTRA_TITLE)?.toString() ?: ""
        val text  = extras.getCharSequence(Notification.EXTRA_TEXT)?.toString()  ?: ""

        if (text.isBlank()) return
        DKLogger.d("Notification from $pkg – $title: $text")

        val appName = friendlyName(pkg)
        val toSpeak = "Boss, new message from $appName. $title says: $text"
        tts?.speak(toSpeak, TextToSpeech.QUEUE_ADD, null, sbn.key)

        (application as DKApplication).memoryManager.logInteraction("Notification[$appName]", "$title: $text")
    }

    override fun onNotificationRemoved(sbn: StatusBarNotification) {}

    override fun onDestroy() {
        tts?.shutdown()
        super.onDestroy()
    }

    private fun friendlyName(pkg: String) = when (pkg) {
        "com.whatsapp", "com.whatsapp.w4b" -> "WhatsApp"
        "com.instagram.android"            -> "Instagram"
        "com.facebook.katana"              -> "Facebook"
        "com.google.android.gm"            -> "Gmail"
        "com.microsoft.teams"              -> "Teams"
        "com.slack"                        -> "Slack"
        "org.telegram.messenger"           -> "Telegram"
        else -> pkg
    }
}
