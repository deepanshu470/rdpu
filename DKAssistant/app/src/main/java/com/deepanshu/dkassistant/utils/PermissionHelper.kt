package com.deepanshu.dkassistant.utils

import android.Manifest
import android.content.Context
import android.content.pm.PackageManager
import android.os.Build
import android.provider.Settings
import androidx.core.app.NotificationManagerCompat
import androidx.core.content.ContextCompat

/**
 * Permission Helper
 *
 * Utility class for checking and managing app permissions
 *
 * @author Deepanshu (Boss)
 */
class PermissionHelper(private val context: Context) {

    /**
     * Check if microphone permission is granted
     */
    fun hasMicrophonePermission(): Boolean {
        return ContextCompat.checkSelfPermission(
            context,
            Manifest.permission.RECORD_AUDIO
        ) == PackageManager.PERMISSION_GRANTED
    }

    /**
     * Check if camera permission is granted
     */
    fun hasCameraPermission(): Boolean {
        return ContextCompat.checkSelfPermission(
            context,
            Manifest.permission.CAMERA
        ) == PackageManager.PERMISSION_GRANTED
    }

    /**
     * Check if notification listener access is granted
     */
    fun hasNotificationAccess(): Boolean {
        val enabledListeners = NotificationManagerCompat.getEnabledListenerPackages(context)
        return enabledListeners.contains(context.packageName)
    }

    /**
     * Check if overlay permission is granted
     */
    fun hasOverlayPermission(): Boolean {
        return if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            Settings.canDrawOverlays(context)
        } else {
            true
        }
    }

    /**
     * Check if notification permission is granted (Android 13+)
     */
    fun hasNotificationPermission(): Boolean {
        return if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            ContextCompat.checkSelfPermission(
                context,
                Manifest.permission.POST_NOTIFICATIONS
            ) == PackageManager.PERMISSION_GRANTED
        } else {
            true
        }
    }

    /**
     * Check if all required permissions are granted
     */
    fun allPermissionsGranted(): Boolean {
        return hasMicrophonePermission() &&
               hasCameraPermission() &&
               hasNotificationAccess() &&
               hasOverlayPermission() &&
               hasNotificationPermission()
    }

    /**
     * Get list of missing permissions
     */
    fun getMissingPermissions(): List<String> {
        val missing = mutableListOf<String>()

        if (!hasMicrophonePermission()) missing.add("Microphone")
        if (!hasCameraPermission()) missing.add("Camera")
        if (!hasNotificationAccess()) missing.add("Notification Access")
        if (!hasOverlayPermission()) missing.add("Display Over Other Apps")
        if (!hasNotificationPermission()) missing.add("Post Notifications")

        return missing
    }

    /**
     * Get permission status summary
     */
    fun getPermissionSummary(): String {
        val missing = getMissingPermissions()

        return if (missing.isEmpty()) {
            "All permissions granted ✓"
        } else {
            "Missing: ${missing.joinToString(", ")}"
        }
    }
}
