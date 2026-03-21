package com.dk.assistant

import android.app.Application
import android.app.NotificationChannel
import android.app.NotificationManager
import android.os.Build
import androidx.security.crypto.EncryptedSharedPreferences
import androidx.security.crypto.MasterKey
import com.dk.assistant.memory.MemoryManager
import com.dk.assistant.util.DKLogger

/**
 * DKApplication – Application class that initialises global singletons.
 */
class DKApplication : Application() {

    companion object {
        // Notification channel IDs
        const val CHANNEL_ASSISTANT   = "dk_assistant_channel"
        const val CHANNEL_NOTIFICATION = "dk_notification_channel"
        const val CHANNEL_CALL        = "dk_call_channel"
        const val CHANNEL_AI          = "dk_ai_channel"

        lateinit var instance: DKApplication
            private set
    }

    /** Encrypted preferences for storing API keys, PIN, etc. */
    val securePrefs by lazy {
        val masterKey = MasterKey.Builder(this)
            .setKeyScheme(MasterKey.KeyScheme.AES256_GCM)
            .build()
        EncryptedSharedPreferences.create(
            this,
            "dk_secure_prefs",
            masterKey,
            EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
            EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
        )
    }

    val memoryManager by lazy { MemoryManager(this) }

    override fun onCreate() {
        super.onCreate()
        instance = this
        createNotificationChannels()
        DKLogger.init(this)
    }

    private fun createNotificationChannels() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val nm = getSystemService(NotificationManager::class.java)

            nm.createNotificationChannel(
                NotificationChannel(
                    CHANNEL_ASSISTANT,
                    "DK Assistant",
                    NotificationManager.IMPORTANCE_LOW
                ).apply { description = "Persistent DK Assistant service notification" }
            )

            nm.createNotificationChannel(
                NotificationChannel(
                    CHANNEL_NOTIFICATION,
                    "Notifications Reader",
                    NotificationManager.IMPORTANCE_DEFAULT
                ).apply { description = "DK reads incoming notifications aloud" }
            )

            nm.createNotificationChannel(
                NotificationChannel(
                    CHANNEL_CALL,
                    "Call Manager",
                    NotificationManager.IMPORTANCE_HIGH
                ).apply { description = "DK incoming / outgoing call alerts" }
            )

            nm.createNotificationChannel(
                NotificationChannel(
                    CHANNEL_AI,
                    "AI Brain",
                    NotificationManager.IMPORTANCE_LOW
                ).apply { description = "DK AI thinking / response channel" }
            )
        }
    }
}
