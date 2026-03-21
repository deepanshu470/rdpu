package com.dk.assistant.service

import android.app.Notification
import android.app.PendingIntent
import android.app.Service
import android.content.Intent
import android.media.AudioManager
import android.os.Bundle
import android.os.IBinder
import android.speech.RecognitionListener
import android.speech.RecognizerIntent
import android.speech.SpeechRecognizer
import android.speech.tts.TextToSpeech
import androidx.core.app.NotificationCompat
import com.dk.assistant.DKApplication
import com.dk.assistant.R
import com.dk.assistant.ai.AIBrainService
import com.dk.assistant.command.CommandProcessor
import com.dk.assistant.hardware.HardwareControlManager
import com.dk.assistant.memory.MemoryManager
import com.dk.assistant.ui.MainActivity
import com.dk.assistant.util.DKLogger
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.SupervisorJob
import kotlinx.coroutines.cancel
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch
import java.util.Locale

/**
 * DKAssistantService – Persistent foreground service that:
 *   • Continuously listens for the wake word "DK"
 *   • Processes voice commands via [CommandProcessor]
 *   • Delegates AI questions to [AIBrainService]
 *   • Speaks responses through Android TTS
 */
class DKAssistantService : Service(), RecognitionListener, TextToSpeech.OnInitListener {

    companion object {
        const val ACTION_START  = "com.dk.assistant.START"
        const val ACTION_STOP   = "com.dk.assistant.STOP"
        const val ACTION_SPEAK  = "com.dk.assistant.SPEAK"
        const val EXTRA_TEXT    = "extra_text"
        private const val NOTIFICATION_ID = 1001
        private const val WAKE_WORD = "dk"
    }

    private val serviceScope = CoroutineScope(SupervisorJob() + Dispatchers.Main)

    private var speechRecognizer: SpeechRecognizer? = null
    private var tts: TextToSpeech? = null
    private var isListening   = false
    private var isAwake       = false   // true after wake word detected
    private var isTtsBusy     = false

    private lateinit var commandProcessor: CommandProcessor
    private lateinit var hardwareManager : HardwareControlManager
    private lateinit var memoryManager   : MemoryManager

    // ──────────────────────────────────────────────────────────────
    override fun onCreate() {
        super.onCreate()
        commandProcessor = CommandProcessor(this)
        hardwareManager  = HardwareControlManager(this)
        memoryManager    = (application as DKApplication).memoryManager
        tts              = TextToSpeech(this, this)
        initSpeechRecognizer()
        startForeground(NOTIFICATION_ID, buildNotification("DK is listening…"))
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        when (intent?.action) {
            ACTION_STOP  -> stopSelf()
            ACTION_SPEAK -> {
                val text = intent.getStringExtra(EXTRA_TEXT) ?: return START_STICKY
                speak(text)
            }
        }
        if (!isListening) startListening()
        return START_STICKY
    }

    override fun onBind(intent: Intent?): IBinder? = null

    override fun onDestroy() {
        serviceScope.cancel()
        speechRecognizer?.destroy()
        tts?.shutdown()
        super.onDestroy()
    }

    // ──────────────────────────────────────────────────────────────
    // TTS
    // ──────────────────────────────────────────────────────────────
    override fun onInit(status: Int) {
        if (status == TextToSpeech.SUCCESS) {
            tts?.language = Locale.US
            speak("DK Assistant is ready, Boss.")
        }
    }

    fun speak(text: String) {
        DKLogger.d("DK speaks: $text")
        memoryManager.logInteraction("DK", text)
        isTtsBusy = true
        tts?.speak(text, TextToSpeech.QUEUE_ADD, null, "dk_utterance")
        // Resume listening shortly after speaking
        serviceScope.launch {
            delay(((text.length / 15) * 1000L).coerceAtLeast(1500L))
            isTtsBusy = false
            startListening()
        }
    }

    // ──────────────────────────────────────────────────────────────
    // Speech Recognition
    // ──────────────────────────────────────────────────────────────
    private fun initSpeechRecognizer() {
        speechRecognizer?.destroy()
        speechRecognizer = SpeechRecognizer.createSpeechRecognizer(this)
        speechRecognizer?.setRecognitionListener(this)
    }

    private fun startListening() {
        if (isTtsBusy) return
        isListening = true
        val intent = Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH).apply {
            putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL, RecognizerIntent.LANGUAGE_MODEL_FREE_FORM)
            putExtra(RecognizerIntent.EXTRA_LANGUAGE, "en-IN")
            putExtra(RecognizerIntent.EXTRA_PARTIAL_RESULTS, true)
            putExtra(RecognizerIntent.EXTRA_MAX_RESULTS, 3)
        }
        speechRecognizer?.startListening(intent)
    }

    // ── RecognitionListener callbacks ──
    override fun onReadyForSpeech(params: Bundle?) {
        DKLogger.d("SpeechRecognizer ready")
    }

    override fun onBeginningOfSpeech() {}

    override fun onRmsChanged(rmsdB: Float) {}

    override fun onBufferReceived(buffer: ByteArray?) {}

    override fun onEndOfSpeech() {
        isListening = false
    }

    override fun onError(error: Int) {
        isListening = false
        DKLogger.w("SpeechRecognizer error: $error")
        // Restart after short delay unless stopping
        serviceScope.launch {
            delay(500)
            startListening()
        }
    }

    override fun onResults(results: Bundle?) {
        isListening = false
        val matches = results?.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION) ?: return
        val text    = matches.firstOrNull() ?: return
        DKLogger.d("Recognized: $text")
        handleSpeechResult(text)
    }

    override fun onPartialResults(partialResults: Bundle?) {
        val partial = partialResults
            ?.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION)
            ?.firstOrNull() ?: return
        // Check for wake word in partial results for faster response
        if (!isAwake && partial.lowercase().contains(WAKE_WORD)) {
            isAwake = true
            speak("Yes Boss?")
        }
    }

    override fun onEvent(eventType: Int, params: Bundle?) {}

    // ──────────────────────────────────────────────────────────────
    // Command Handling
    // ──────────────────────────────────────────────────────────────
    private fun handleSpeechResult(text: String) {
        val lower = text.lowercase().trim()
        memoryManager.logInteraction("Boss", text)

        // Wake word check
        if (!isAwake) {
            if (lower.contains(WAKE_WORD)) {
                isAwake = true
                speak("Yes Boss, tell me?")
            } else {
                startListening()
            }
            return
        }

        // Reset wake state after receiving a command
        isAwake = false

        // Delegate to CommandProcessor
        serviceScope.launch {
            val response = commandProcessor.process(text)
            if (response.isNotBlank()) speak(response)
            else startListening()
        }
    }

    // ──────────────────────────────────────────────────────────────
    // Notification
    // ──────────────────────────────────────────────────────────────
    private fun buildNotification(contentText: String): Notification {
        val pi = PendingIntent.getActivity(
            this, 0,
            Intent(this, MainActivity::class.java),
            PendingIntent.FLAG_IMMUTABLE
        )
        return NotificationCompat.Builder(this, DKApplication.CHANNEL_ASSISTANT)
            .setContentTitle("DK Assistant")
            .setContentText(contentText)
            .setSmallIcon(R.drawable.ic_dk_logo)
            .setContentIntent(pi)
            .setOngoing(true)
            .build()
    }
}
