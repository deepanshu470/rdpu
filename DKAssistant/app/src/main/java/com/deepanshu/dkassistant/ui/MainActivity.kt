package com.deepanshu.dkassistant.ui

import android.Manifest
import android.content.Intent
import android.content.pm.PackageManager
import android.net.Uri
import android.os.Build
import android.os.Bundle
import android.provider.Settings
import android.widget.Button
import android.widget.Switch
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import com.deepanshu.dkassistant.R
import com.deepanshu.dkassistant.manager.AIBrainManager
import com.deepanshu.dkassistant.manager.BiometricAuthManager
import com.deepanshu.dkassistant.service.DKAssistantService
import com.deepanshu.dkassistant.service.FloatingWidgetService
import com.deepanshu.dkassistant.utils.PermissionHelper

/**
 * Main Activity - DK Assistant Dashboard
 *
 * Features:
 * - Futuristic dark-themed UI
 * - Permission management toggles
 * - Service control (start/stop)
 * - AI configuration
 * - Command history viewer
 * - Status indicators
 *
 * @author Deepanshu (Boss)
 */
class MainActivity : AppCompatActivity() {

    companion object {
        private const val REQUEST_RECORD_AUDIO = 1001
        private const val REQUEST_CAMERA = 1002
        private const val REQUEST_NOTIFICATION = 1003
        private const val REQUEST_OVERLAY = 1004
    }

    private lateinit var biometricAuthManager: BiometricAuthManager
    private lateinit var aiBrainManager: AIBrainManager
    private lateinit var permissionHelper: PermissionHelper

    // UI Elements
    private lateinit var statusText: TextView
    private lateinit var ownerNameText: TextView
    private lateinit var aiProviderText: TextView
    private lateinit var authStatusText: TextView

    private lateinit var switchMicrophone: Switch
    private lateinit var switchCamera: Switch
    private lateinit var switchNotification: Switch
    private lateinit var switchOverlay: Switch
    private lateinit var switchService: Switch

    private lateinit var btnConfigureAI: Button
    private lateinit var btnViewHistory: Button
    private lateinit var btnTestVoice: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        // Initialize managers
        biometricAuthManager = BiometricAuthManager(this)
        aiBrainManager = AIBrainManager(this)
        permissionHelper = PermissionHelper(this)

        // Initialize UI
        initializeViews()
        setupListeners()
        updateUI()
    }

    private fun initializeViews() {
        // Status TextViews
        statusText = findViewById(R.id.tv_status)
        ownerNameText = findViewById(R.id.tv_owner_name)
        aiProviderText = findViewById(R.id.tv_ai_provider)
        authStatusText = findViewById(R.id.tv_auth_status)

        // Permission Switches
        switchMicrophone = findViewById(R.id.switch_microphone)
        switchCamera = findViewById(R.id.switch_camera)
        switchNotification = findViewById(R.id.switch_notification)
        switchOverlay = findViewById(R.id.switch_overlay)
        switchService = findViewById(R.id.switch_service)

        // Buttons
        btnConfigureAI = findViewById(R.id.btn_configure_ai)
        btnViewHistory = findViewById(R.id.btn_view_history)
        btnTestVoice = findViewById(R.id.btn_test_voice)
    }

    private fun setupListeners() {
        // Microphone permission
        switchMicrophone.setOnCheckedChangeListener { _, isChecked ->
            if (isChecked) {
                requestMicrophonePermission()
            } else {
                openAppSettings()
            }
        }

        // Camera permission
        switchCamera.setOnCheckedChangeListener { _, isChecked ->
            if (isChecked) {
                requestCameraPermission()
            } else {
                openAppSettings()
            }
        }

        // Notification access
        switchNotification.setOnCheckedChangeListener { _, isChecked ->
            if (isChecked) {
                openNotificationSettings()
            } else {
                showToast("Please disable manually in Settings")
            }
        }

        // Overlay permission
        switchOverlay.setOnCheckedChangeListener { _, isChecked ->
            if (isChecked) {
                requestOverlayPermission()
            } else {
                openAppSettings()
            }
        }

        // Service toggle
        switchService.setOnCheckedChangeListener { _, isChecked ->
            if (isChecked) {
                startDKServices()
            } else {
                stopDKServices()
            }
        }

        // Configure AI button
        btnConfigureAI.setOnClickListener {
            showAIConfigDialog()
        }

        // View history button
        btnViewHistory.setOnClickListener {
            showHistoryDialog()
        }

        // Test voice button
        btnTestVoice.setOnClickListener {
            testVoiceCommand()
        }
    }

    private fun updateUI() {
        // Update owner name
        ownerNameText.text = "Owner: ${biometricAuthManager.getOwnerName()}"

        // Update AI provider
        aiProviderText.text = "AI: ${aiBrainManager.getProviderName()} - ${aiBrainManager.getCurrentModel()}"

        // Update auth status
        authStatusText.text = biometricAuthManager.getAuthStatus()

        // Update permission switches
        switchMicrophone.isChecked = permissionHelper.hasMicrophonePermission()
        switchCamera.isChecked = permissionHelper.hasCameraPermission()
        switchNotification.isChecked = permissionHelper.hasNotificationAccess()
        switchOverlay.isChecked = permissionHelper.hasOverlayPermission()

        // Update status
        updateStatusText()
    }

    private fun updateStatusText() {
        val allPermissionsGranted = permissionHelper.allPermissionsGranted()

        if (allPermissionsGranted) {
            statusText.text = "✓ All systems operational"
            statusText.setTextColor(getColor(R.color.success_green))
        } else {
            statusText.text = "⚠ Permissions required"
            statusText.setTextColor(getColor(R.color.warning_yellow))
        }
    }

    private fun requestMicrophonePermission() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            ActivityCompat.requestPermissions(
                this,
                arrayOf(Manifest.permission.RECORD_AUDIO),
                REQUEST_RECORD_AUDIO
            )
        }
    }

    private fun requestCameraPermission() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            ActivityCompat.requestPermissions(
                this,
                arrayOf(Manifest.permission.CAMERA),
                REQUEST_CAMERA
            )
        }
    }

    private fun requestOverlayPermission() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            if (!Settings.canDrawOverlays(this)) {
                val intent = Intent(
                    Settings.ACTION_MANAGE_OVERLAY_PERMISSION,
                    Uri.parse("package:$packageName")
                )
                startActivityForResult(intent, REQUEST_OVERLAY)
            }
        }
    }

    private fun openNotificationSettings() {
        val intent = Intent("android.settings.ACTION_NOTIFICATION_LISTENER_SETTINGS")
        startActivity(intent)
    }

    private fun openAppSettings() {
        val intent = Intent(Settings.ACTION_APPLICATION_DETAILS_SETTINGS).apply {
            data = Uri.fromParts("package", packageName, null)
        }
        startActivity(intent)
    }

    private fun startDKServices() {
        if (!permissionHelper.allPermissionsGranted()) {
            showToast("Please grant all permissions first")
            switchService.isChecked = false
            return
        }

        // Start DK Assistant Service
        val assistantIntent = Intent(this, DKAssistantService::class.java)
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            startForegroundService(assistantIntent)
        } else {
            startService(assistantIntent)
        }

        // Start Floating Widget Service
        val widgetIntent = Intent(this, FloatingWidgetService::class.java)
        startService(widgetIntent)

        showToast("DK Assistant services started")
        statusText.text = "✓ DK Assistant is active"
        statusText.setTextColor(getColor(R.color.success_green))
    }

    private fun stopDKServices() {
        stopService(Intent(this, DKAssistantService::class.java))
        stopService(Intent(this, FloatingWidgetService::class.java))

        showToast("DK Assistant services stopped")
        statusText.text = "⊗ DK Assistant is inactive"
        statusText.setTextColor(getColor(R.color.error_red))
    }

    private fun showAIConfigDialog() {
        val dialogView = layoutInflater.inflate(R.layout.dialog_ai_config, null)

        // Configure dialog views
        // ... (implementation details)

        AlertDialog.Builder(this, R.style.DarkAlertDialog)
            .setTitle("Configure AI Brain")
            .setView(dialogView)
            .setPositiveButton("Save") { dialog, _ ->
                // Save configuration
                showToast("AI configuration saved")
                updateUI()
                dialog.dismiss()
            }
            .setNegativeButton("Cancel") { dialog, _ ->
                dialog.dismiss()
            }
            .show()
    }

    private fun showHistoryDialog() {
        val history = aiBrainManager.getHistory()

        if (history.isEmpty()) {
            showToast("No conversation history yet")
            return
        }

        val historyText = history.joinToString("\n\n") { (role, content) ->
            "${role.uppercase()}: $content"
        }

        AlertDialog.Builder(this, R.style.DarkAlertDialog)
            .setTitle("Conversation History")
            .setMessage(historyText)
            .setPositiveButton("Clear History") { dialog, _ ->
                aiBrainManager.clearHistory()
                showToast("History cleared")
                dialog.dismiss()
            }
            .setNegativeButton("Close") { dialog, _ ->
                dialog.dismiss()
            }
            .show()
    }

    private fun testVoiceCommand() {
        val intent = Intent(this, DKAssistantService::class.java).apply {
            action = DKAssistantService.ACTION_PROCESS_COMMAND
            putExtra("command", "What is the capital of France?")
        }

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            startForegroundService(intent)
        } else {
            startService(intent)
        }

        showToast("Testing voice command...")
    }

    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<out String>,
        grantResults: IntArray
    ) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults)

        when (requestCode) {
            REQUEST_RECORD_AUDIO, REQUEST_CAMERA -> {
                updateUI()
            }
        }
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)

        if (requestCode == REQUEST_OVERLAY) {
            updateUI()
        }
    }

    override fun onResume() {
        super.onResume()
        updateUI()
    }

    private fun showToast(message: String) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
    }
}
