package com.dk.assistant.notification

import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import android.provider.Telephony
import android.speech.tts.TextToSpeech
import com.dk.assistant.DKApplication
import com.dk.assistant.util.DKLogger
import java.util.Locale

class SmsReceiver : BroadcastReceiver() {
    override fun onReceive(context: Context, intent: Intent) {
        if (intent.action != Telephony.Sms.Intents.SMS_RECEIVED_ACTION) return
        val messages = Telephony.Sms.Intents.getMessagesFromIntent(intent)
        messages.forEach { sms ->
            val sender = sms.originatingAddress ?: "Unknown"
            val body   = sms.messageBody ?: ""
            DKLogger.d("SMS from $sender: $body")
            (context.applicationContext as DKApplication).memoryManager
                .logInteraction("SMS[$sender]", body)
        }
    }
}
