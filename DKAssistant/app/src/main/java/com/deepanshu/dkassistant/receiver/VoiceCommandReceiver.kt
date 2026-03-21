package com.deepanshu.dkassistant.receiver

import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent

/**
 * Voice Command Receiver
 *
 * Receives voice commands from other components
 */
class VoiceCommandReceiver : BroadcastReceiver() {

    override fun onReceive(context: Context?, intent: Intent?) {
        if (context == null || intent == null) return

        val command = intent.getStringExtra("command")
        // Handle voice command
    }
}
