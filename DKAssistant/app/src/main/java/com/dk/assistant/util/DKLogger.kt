package com.dk.assistant.util

import android.content.Context
import android.util.Log

object DKLogger {
    private const val TAG = "DKAssistant"
    private var enabled = true

    fun init(context: Context) { enabled = true }
    fun d(msg: String) { if (enabled) Log.d(TAG, msg) }
    fun w(msg: String) { if (enabled) Log.w(TAG, msg) }
    fun e(msg: String, t: Throwable? = null) { if (enabled) Log.e(TAG, msg, t) }
}
