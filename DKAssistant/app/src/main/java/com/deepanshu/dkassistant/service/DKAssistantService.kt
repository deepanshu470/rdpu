package com.deepanshu.dkassistant.service

import android.app.*
import android.content.Context
import android.content.Intent
import android.hardware.camera2.CameraManager
import android.os.*
import android.speech.RecognitionListener
import android.speech.RecognizerIntent
import android.speech.SpeechRecognizer
import android.speech.tts.TextToSpeech
import androidx.core.app.NotificationCompat
import com.deepanshu.dkassistant.R
import com.deepanshu.dkassistant.manager.AIBrainManager
import com.deepanshu.dkassistant.manager.BiometricAuthManager
import com.deepanshu.dkassistant.ui.MainActivity
import kotlinx.coroutines.*
import java.util.*

/**
 * DK Assistant Foreground Service
 *
 * This service runs in the foreground and handles:
 * - Continuous voice listening for wake word "DK"
 * - Voice command processing
 * - App launching via Intent
 * - Hardware control (flashlight)
 * - AI conversations via LLM integration
 * - Text-to-Speech responses
 *
 * @author Deepanshu (Boss)
 */
class DKAssistantService : Service(), TextToSpeech.OnInitListener {

    companion object {
        private const val NOTIFICATION_ID = 1001
        private const val CHANNEL_ID = "DKAssistantChannel"
        private const val WAKE_WORD = "dk"

        const val ACTION_START_LISTENING = "com.deepanshu.dkassistant.START_LISTENING"
        const val ACTION_STOP_LISTENING = "com.deepanshu.dkassistant.STOP_LISTENING"
        const val ACTION_PROCESS_COMMAND = "com.deepanshu.dkassistant.PROCESS_COMMAND"
    }

    private lateinit var speechRecognizer: SpeechRecognizer
    private lateinit var tts: TextToSpeech
    private lateinit var aiBrainManager: AIBrainManager
    private lateinit var cameraManager: CameraManager
    private lateinit var wakeLock: PowerManager.WakeLock

    private var isListening = false
    private var isTtsReady = false
    private var isFlashlightOn = false
    private var cameraId: String? = null

    private val serviceScope = CoroutineScope(Dispatchers.Main + SupervisorJob())
    private val handler = Handler(Looper.getMainLooper())

    override fun onCreate() {
        super.onCreate()

        // Initialize TTS
        tts = TextToSpeech(this, this)

        // Initialize AI Brain
        aiBrainManager = AIBrainManager(this)

        // Initialize Camera Manager for flashlight
        cameraManager = getSystemService(Context.CAMERA_SERVICE) as CameraManager
        try {
            cameraId = cameraManager.cameraIdList.firstOrNull()
        } catch (e: Exception) {
            e.printStackTrace()
        }

        // Acquire wake lock to keep service running
        val powerManager = getSystemService(Context.POWER_SERVICE) as PowerManager
        wakeLock = powerManager.newWakeLock(
            PowerManager.PARTIAL_WAKE_LOCK,
            "DKAssistant::WakeLock"
        )
        wakeLock.acquire(10*60*1000L /*10 minutes*/)

        // Initialize Speech Recognizer
        initializeSpeechRecognizer()

        // Create notification channel
        createNotificationChannel()
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        // Start as foreground service
        startForeground(NOTIFICATION_ID, createNotification("DK Assistant is listening..."))

        when (intent?.action) {
            ACTION_START_LISTENING -> startListening()
            ACTION_STOP_LISTENING -> stopListening()
            ACTION_PROCESS_COMMAND -> {
                val command = intent.getStringExtra("command")
                command?.let { processVoiceCommand(it) }
            }
            else -> startListening()
        }

        return START_STICKY
    }

    override fun onBind(intent: Intent?): IBinder? = null

    override fun onInit(status: Int) {
        if (status == TextToSpeech.SUCCESS) {
            val result = tts.setLanguage(Locale.US)
            isTtsReady = result != TextToSpeech.LANG_MISSING_DATA &&
                        result != TextToSpeech.LANG_NOT_SUPPORTED

            // Configure TTS
            tts.setPitch(1.0f)
            tts.setSpeechRate(1.0f)

            if (isTtsReady) {
                speak("DK Assistant initialized. Say DK to activate me.")
            }
        }
    }

    private fun initializeSpeechRecognizer() {
        speechRecognizer = SpeechRecognizer.createSpeechRecognizer(this)
        speechRecognizer.setRecognitionListener(object : RecognitionListener {
            override fun onReadyForSpeech(params: Bundle?) {
                updateNotification("Listening for wake word...")
            }

            override fun onBeginningOfSpeech() {}

            override fun onRmsChanged(rmsdB: Float) {}

            override fun onBufferReceived(buffer: ByteArray?) {}

            override fun onEndOfSpeech() {}

            override fun onError(error: Int) {
                // Restart listening after error
                handler.postDelayed({
                    if (isListening) startListening()
                }, 1000)
            }

            override fun onResults(results: Bundle?) {
                val matches = results?.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION)
                matches?.firstOrNull()?.let { spokenText ->
                    handleSpeechResult(spokenText.lowercase())
                }

                // Continue listening for wake word
                handler.postDelayed({
                    if (isListening) startListening()
                }, 500)
            }

            override fun onPartialResults(partialResults: Bundle?) {}

            override fun onEvent(eventType: Int, params: Bundle?) {}
        })
    }

    private fun startListening() {
        if (!isListening) {
            isListening = true
            val intent = Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH).apply {
                putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL,
                        RecognizerIntent.LANGUAGE_MODEL_FREE_FORM)
                putExtra(RecognizerIntent.EXTRA_LANGUAGE, Locale.getDefault())
                putExtra(RecognizerIntent.EXTRA_PARTIAL_RESULTS, true)
            }

            try {
                speechRecognizer.startListening(intent)
            } catch (e: Exception) {
                e.printStackTrace()
                isListening = false
            }
        }
    }

    private fun stopListening() {
        isListening = false
        try {
            speechRecognizer.stopListening()
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun handleSpeechResult(spokenText: String) {
        // Check for wake word
        if (spokenText.contains(WAKE_WORD) ||
            spokenText.contains("hey $WAKE_WORD")) {

            vibrate()
            speak("Yes Boss, how can I help you?")

            // Start listening for command
            updateNotification("Waiting for command...")
            listenForCommand()
        }
    }

    private fun listenForCommand() {
        val intent = Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH).apply {
            putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL,
                    RecognizerIntent.LANGUAGE_MODEL_FREE_FORM)
            putExtra(RecognizerIntent.EXTRA_LANGUAGE, Locale.getDefault())
            putExtra(RecognizerIntent.EXTRA_SPEECH_INPUT_COMPLETE_SILENCE_LENGTH_MILLIS, 3000)
        }

        val commandRecognizer = SpeechRecognizer.createSpeechRecognizer(this)
        commandRecognizer.setRecognitionListener(object : RecognitionListener {
            override fun onReadyForSpeech(params: Bundle?) {}
            override fun onBeginningOfSpeech() {}
            override fun onRmsChanged(rmsdB: Float) {}
            override fun onBufferReceived(buffer: ByteArray?) {}
            override fun onEndOfSpeech() {}

            override fun onError(error: Int) {
                speak("Sorry Boss, I didn't catch that.")
                commandRecognizer.destroy()
            }

            override fun onResults(results: Bundle?) {
                val matches = results?.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION)
                matches?.firstOrNull()?.let { command ->
                    processVoiceCommand(command)
                }
                commandRecognizer.destroy()
            }

            override fun onPartialResults(partialResults: Bundle?) {}
            override fun onEvent(eventType: Int, params: Bundle?) {}
        })

        commandRecognizer.startListening(intent)
    }

    private fun processVoiceCommand(command: String) {
        val lowerCommand = command.lowercase()

        updateNotification("Processing: $command")

        when {
            // App launching commands
            lowerCommand.contains("open whatsapp") -> {
                launchApp("com.whatsapp", "WhatsApp")
            }
            lowerCommand.contains("open instagram") -> {
                launchApp("com.instagram.android", "Instagram")
            }
            lowerCommand.contains("open chrome") -> {
                launchApp("com.android.chrome", "Chrome")
            }
            lowerCommand.contains("open gmail") -> {
                launchApp("com.google.android.gm", "Gmail")
            }
            lowerCommand.contains("open youtube") -> {
                launchApp("com.google.android.youtube", "YouTube")
            }

            // Flashlight control
            lowerCommand.contains("turn on") &&
            (lowerCommand.contains("flashlight") || lowerCommand.contains("torch")) -> {
                toggleFlashlight(true)
            }
            lowerCommand.contains("turn off") &&
            (lowerCommand.contains("flashlight") || lowerCommand.contains("torch")) -> {
                toggleFlashlight(false)
            }

            // AI conversation - anything else goes to the AI brain
            else -> {
                handleAIQuery(command)
            }
        }
    }

    private fun launchApp(packageName: String, appName: String) {
        try {
            val launchIntent = packageManager.getLaunchIntentForPackage(packageName)
            if (launchIntent != null) {
                launchIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK)
                startActivity(launchIntent)
                speak("Opening $appName for you, Boss.")
            } else {
                speak("Sorry Boss, $appName is not installed on your device.")
            }
        } catch (e: Exception) {
            speak("Sorry Boss, I couldn't open $appName.")
            e.printStackTrace()
        }
    }

    private fun toggleFlashlight(turnOn: Boolean) {
        try {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M && cameraId != null) {
                cameraManager.setTorchMode(cameraId!!, turnOn)
                isFlashlightOn = turnOn
                speak(if (turnOn) "Flashlight turned on, Boss." else "Flashlight turned off, Boss.")
            } else {
                speak("Sorry Boss, flashlight control is not available.")
            }
        } catch (e: Exception) {
            speak("Sorry Boss, I couldn't control the flashlight.")
            e.printStackTrace()
        }
    }

    private fun handleAIQuery(query: String) {
        speak("Let me think about that, Boss.")

        serviceScope.launch {
            try {
                val response = aiBrainManager.getAIResponse(query)
                speak(response)
            } catch (e: Exception) {
                speak("Sorry Boss, I'm having trouble connecting to my brain right now.")
                e.printStackTrace()
            }
        }
    }

    private fun speak(text: String) {
        if (isTtsReady) {
            tts.speak(text, TextToSpeech.QUEUE_ADD, null, null)
        }
    }

    private fun vibrate() {
        val vibrator = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.S) {
            val vibratorManager = getSystemService(Context.VIBRATOR_MANAGER_SERVICE) as VibratorManager
            vibratorManager.defaultVibrator
        } else {
            @Suppress("DEPRECATION")
            getSystemService(Context.VIBRATOR_SERVICE) as Vibrator
        }

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            vibrator.vibrate(VibrationEffect.createOneShot(200, VibrationEffect.DEFAULT_AMPLITUDE))
        } else {
            @Suppress("DEPRECATION")
            vibrator.vibrate(200)
        }
    }

    private fun createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val channel = NotificationChannel(
                CHANNEL_ID,
                "DK Assistant Service",
                NotificationManager.IMPORTANCE_LOW
            ).apply {
                description = "DK Assistant is running in the background"
                setShowBadge(false)
            }

            val notificationManager = getSystemService(NotificationManager::class.java)
            notificationManager.createNotificationChannel(channel)
        }
    }

    private fun createNotification(contentText: String): Notification {
        val intent = Intent(this, MainActivity::class.java)
        val pendingIntent = PendingIntent.getActivity(
            this, 0, intent,
            PendingIntent.FLAG_IMMUTABLE or PendingIntent.FLAG_UPDATE_CURRENT
        )

        return NotificationCompat.Builder(this, CHANNEL_ID)
            .setContentTitle("DK Assistant")
            .setContentText(contentText)
            .setSmallIcon(R.drawable.ic_assistant)
            .setContentIntent(pendingIntent)
            .setOngoing(true)
            .setPriority(NotificationCompat.PRIORITY_LOW)
            .build()
    }

    private fun updateNotification(contentText: String) {
        val notificationManager = getSystemService(NotificationManager::class.java)
        notificationManager.notify(NOTIFICATION_ID, createNotification(contentText))
    }

    override fun onDestroy() {
        super.onDestroy()

        // Cleanup
        isListening = false
        speechRecognizer.destroy()
        tts.stop()
        tts.shutdown()

        if (wakeLock.isHeld) {
            wakeLock.release()
        }

        // Turn off flashlight if on
        if (isFlashlightOn) {
            toggleFlashlight(false)
        }

        serviceScope.cancel()
    }
}
