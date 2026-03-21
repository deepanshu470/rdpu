package com.dk.assistant.auth

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.biometric.BiometricManager
import androidx.biometric.BiometricPrompt
import androidx.core.content.ContextCompat
import com.dk.assistant.DKApplication
import com.dk.assistant.R
import com.dk.assistant.databinding.ActivityBiometricBinding
import com.dk.assistant.ui.MainActivity

class BiometricAuthActivity : AppCompatActivity() {

    companion object {
        const val EXTRA_DESTINATION = "destination"
        private const val CORRECT_PIN = "1234"   // Default; stored securely after first run
    }

    private lateinit var binding: ActivityBiometricBinding
    private var pinAttempts = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityBiometricBinding.inflate(layoutInflater)
        setContentView(binding.root)

        val biometricManager = BiometricManager.from(this)
        val canAuth = biometricManager.canAuthenticate(
            BiometricManager.Authenticators.BIOMETRIC_STRONG or
            BiometricManager.Authenticators.DEVICE_CREDENTIAL
        )

        if (canAuth == BiometricManager.BIOMETRIC_SUCCESS) {
            showBiometricPrompt()
        } else {
            binding.pinLayout.visibility = android.view.View.VISIBLE
        }

        binding.btnPinConfirm.setOnClickListener {
            val enteredPin = binding.etPin.text.toString()
            val storedPin  = (application as DKApplication).securePrefs
                .getString("owner_pin", CORRECT_PIN)
            if (enteredPin == storedPin) {
                onAuthSuccess()
            } else {
                pinAttempts++
                if (pinAttempts >= 5) {
                    Toast.makeText(this, "Too many attempts. Try again later.", Toast.LENGTH_LONG).show()
                    finish()
                } else {
                    Toast.makeText(this, "Incorrect PIN (${5 - pinAttempts} attempts left)", Toast.LENGTH_SHORT).show()
                }
            }
        }
    }

    private fun showBiometricPrompt() {
        val executor = ContextCompat.getMainExecutor(this)
        val callback = object : BiometricPrompt.AuthenticationCallback() {
            override fun onAuthenticationSucceeded(result: BiometricPrompt.AuthenticationResult) {
                super.onAuthenticationSucceeded(result)
                onAuthSuccess()
            }
            override fun onAuthenticationFailed() {
                super.onAuthenticationFailed()
                binding.pinLayout.visibility = android.view.View.VISIBLE
            }
            override fun onAuthenticationError(errorCode: Int, errString: CharSequence) {
                super.onAuthenticationError(errorCode, errString)
                binding.pinLayout.visibility = android.view.View.VISIBLE
            }
        }
        val prompt = BiometricPrompt(this, executor, callback)
        val info = BiometricPrompt.PromptInfo.Builder()
            .setTitle("DK – Boss Verification")
            .setSubtitle("Authenticate to access DK Assistant")
            .setAllowedAuthenticators(
                BiometricManager.Authenticators.BIOMETRIC_STRONG or
                BiometricManager.Authenticators.DEVICE_CREDENTIAL
            )
            .build()
        prompt.authenticate(info)
    }

    private fun onAuthSuccess() {
        val dest = intent.getStringExtra(EXTRA_DESTINATION)
        val next = if (dest == null) Intent(this, MainActivity::class.java)
                   else Intent().setClassName(packageName, dest)
        startActivity(next)
        finish()
    }
}
