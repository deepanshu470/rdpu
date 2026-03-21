package com.dk.assistant.hardware

import android.bluetooth.BluetoothAdapter
import android.content.Context
import android.hardware.camera2.CameraManager
import android.media.AudioManager
import android.net.wifi.WifiManager
import com.dk.assistant.util.DKLogger

class HardwareControlManager(private val context: Context) {

    private val cameraManager = context.getSystemService(Context.CAMERA_SERVICE) as CameraManager
    private val audioManager  = context.getSystemService(Context.AUDIO_SERVICE) as AudioManager
    private var torchOn = false

    // ── Torch ──────────────────────────────────────────────────────
    fun toggleTorch(): String {
        return try {
            val cameraId = cameraManager.cameraIdList.firstOrNull() ?: return "No camera found."
            torchOn = !torchOn
            cameraManager.setTorchMode(cameraId, torchOn)
            if (torchOn) "Flashlight ON, Boss." else "Flashlight OFF, Boss."
        } catch (e: Exception) {
            DKLogger.e("Torch error", e)
            "Could not toggle flashlight."
        }
    }

    fun setTorch(on: Boolean): String {
        torchOn = on
        return try {
            val cameraId = cameraManager.cameraIdList.firstOrNull() ?: return "No camera found."
            cameraManager.setTorchMode(cameraId, on)
            if (on) "Flashlight ON." else "Flashlight OFF."
        } catch (e: Exception) {
            DKLogger.e("Torch error", e)
            "Could not control flashlight."
        }
    }

    // ── Volume ─────────────────────────────────────────────────────
    fun setVolume(level: Int): String {
        val max = audioManager.getStreamMaxVolume(AudioManager.STREAM_MUSIC)
        val vol = (level.coerceIn(0, 100) * max / 100)
        audioManager.setStreamVolume(AudioManager.STREAM_MUSIC, vol, 0)
        return "Volume set to $level%, Boss."
    }

    fun muteVolume(): String {
        audioManager.adjustStreamVolume(AudioManager.STREAM_MUSIC, AudioManager.ADJUST_MUTE, 0)
        return "Muted."
    }

    fun unmuteVolume(): String {
        audioManager.adjustStreamVolume(AudioManager.STREAM_MUSIC, AudioManager.ADJUST_UNMUTE, 0)
        return "Unmuted."
    }

    // ── Ringer ────────────────────────────────────────────────────
    fun setSilentMode(): String {
        audioManager.ringerMode = AudioManager.RINGER_MODE_SILENT
        return "Silent mode ON."
    }

    fun setNormalMode(): String {
        audioManager.ringerMode = AudioManager.RINGER_MODE_NORMAL
        return "Normal mode ON."
    }
}
