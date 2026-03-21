package com.deepanshu.dkassistant.manager

import android.content.Context
import android.content.SharedPreferences
import android.os.Build
import androidx.biometric.BiometricManager
import androidx.biometric.BiometricPrompt
import androidx.core.content.ContextCompat
import androidx.fragment.app.FragmentActivity

/**
 * Biometric Authentication Manager
 *
 * Handles owner authentication using:
 * - Fingerprint recognition (BiometricPrompt API)
 * - Face recognition (BiometricPrompt API)
 * - Fallback PIN code for security
 *
 * Only "Deepanshu" (the Boss) can execute sensitive commands.
 *
 * @author Deepanshu (Boss)
 */
class BiometricAuthManager(private val context: Context) {

    companion object {
        private const val PREFS_NAME = "dk_auth_prefs"
        private const val KEY_OWNER_PIN = "owner_pin"
        private const val DEFAULT_PIN = "123456"
        private const val KEY_OWNER_NAME = "owner_name"
        private const val DEFAULT_OWNER_NAME = "Deepanshu"
    }

    private val prefs: SharedPreferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
    private var biometricPrompt: BiometricPrompt? = null
    private var promptInfo: BiometricPrompt.PromptInfo? = null

    /**
     * Check if biometric authentication is available on this device
     */
    fun isBiometricAvailable(): Boolean {
        val biometricManager = BiometricManager.from(context)
        return when (biometricManager.canAuthenticate(
            BiometricManager.Authenticators.BIOMETRIC_STRONG or
            BiometricManager.Authenticators.BIOMETRIC_WEAK
        )) {
            BiometricManager.BIOMETRIC_SUCCESS -> true
            else -> false
        }
    }

    /**
     * Authenticate the owner using biometric prompt
     *
     * @param activity The FragmentActivity context
     * @param onSuccess Callback when authentication succeeds
     * @param onError Callback when authentication fails
     * @param onFallbackPIN Callback to trigger PIN fallback
     */
    fun authenticateOwner(
        activity: FragmentActivity,
        onSuccess: () -> Unit,
        onError: (String) -> Unit,
        onFallbackPIN: () -> Unit
    ) {
        if (!isBiometricAvailable()) {
            // Directly go to PIN fallback
            onFallbackPIN()
            return
        }

        val executor = ContextCompat.getMainExecutor(context)

        biometricPrompt = BiometricPrompt(
            activity,
            executor,
            object : BiometricPrompt.AuthenticationCallback() {
                override fun onAuthenticationSucceeded(result: BiometricPrompt.AuthenticationResult) {
                    super.onAuthenticationSucceeded(result)
                    onSuccess()
                }

                override fun onAuthenticationError(errorCode: Int, errString: CharSequence) {
                    super.onAuthenticationError(errorCode, errString)

                    when (errorCode) {
                        BiometricPrompt.ERROR_NEGATIVE_BUTTON -> {
                            // User clicked "Use PIN" button
                            onFallbackPIN()
                        }
                        BiometricPrompt.ERROR_NO_BIOMETRICS -> {
                            // No biometric enrolled
                            onFallbackPIN()
                        }
                        else -> {
                            onError("Authentication error: $errString")
                        }
                    }
                }

                override fun onAuthenticationFailed() {
                    super.onAuthenticationFailed()
                    // Authentication failed but user can retry
                    // Don't call onError here, let them try again
                }
            }
        )

        promptInfo = BiometricPrompt.PromptInfo.Builder()
            .setTitle("DK Assistant - Owner Verification")
            .setSubtitle("Authenticate to confirm you are ${getOwnerName()}")
            .setDescription("This command requires owner authentication")
            .setNegativeButtonText("Use PIN")
            .setAllowedAuthenticators(
                BiometricManager.Authenticators.BIOMETRIC_STRONG or
                BiometricManager.Authenticators.BIOMETRIC_WEAK
            )
            .build()

        biometricPrompt?.authenticate(promptInfo!!)
    }

    /**
     * Verify PIN code
     *
     * @param enteredPin The PIN entered by user
     * @return true if PIN matches, false otherwise
     */
    fun verifyPIN(enteredPin: String): Boolean {
        val storedPin = getOwnerPIN()
        return enteredPin == storedPin
    }

    /**
     * Set a new PIN for the owner
     *
     * @param newPin The new PIN to set (must be 4-6 digits)
     * @return true if PIN was set successfully
     */
    fun setOwnerPIN(newPin: String): Boolean {
        if (newPin.length !in 4..6 || !newPin.all { it.isDigit() }) {
            return false
        }

        prefs.edit().putString(KEY_OWNER_PIN, newPin).apply()
        return true
    }

    /**
     * Get the current owner PIN
     */
    private fun getOwnerPIN(): String {
        return prefs.getString(KEY_OWNER_PIN, DEFAULT_PIN) ?: DEFAULT_PIN
    }

    /**
     * Get the owner's name
     */
    fun getOwnerName(): String {
        return prefs.getString(KEY_OWNER_NAME, DEFAULT_OWNER_NAME) ?: DEFAULT_OWNER_NAME
    }

    /**
     * Set the owner's name
     */
    fun setOwnerName(name: String) {
        prefs.edit().putString(KEY_OWNER_NAME, name).apply()
    }

    /**
     * Reset PIN to default
     */
    fun resetPINToDefault() {
        prefs.edit().putString(KEY_OWNER_PIN, DEFAULT_PIN).apply()
    }

    /**
     * Check if a command requires authentication
     *
     * @param command The voice command to check
     * @return true if authentication is required
     */
    fun requiresAuthentication(command: String): Boolean {
        val sensitiveKeywords = listOf(
            "delete", "uninstall", "factory reset", "clear data",
            "change pin", "settings", "password", "account",
            "payment", "bank", "transfer money"
        )

        val lowerCommand = command.lowercase()
        return sensitiveKeywords.any { lowerCommand.contains(it) }
    }

    /**
     * Quick authentication for simple biometric check
     * Use this when you just need a yes/no result without UI callbacks
     */
    fun quickAuthenticate(
        activity: FragmentActivity,
        onResult: (Boolean) -> Unit
    ) {
        authenticateOwner(
            activity = activity,
            onSuccess = { onResult(true) },
            onError = { onResult(false) },
            onFallbackPIN = { onResult(false) } // For quick auth, no PIN fallback
        )
    }

    /**
     * Get authentication status string for dashboard
     */
    fun getAuthStatus(): String {
        return if (isBiometricAvailable()) {
            "Biometric authentication available"
        } else {
            "PIN authentication only"
        }
    }

    /**
     * Clear all authentication data (use with caution)
     */
    fun clearAuthData() {
        prefs.edit().clear().apply()
    }
}
