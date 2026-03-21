package com.deepanshu.dkassistant.service

import android.app.Service
import android.content.Intent
import android.graphics.PixelFormat
import android.os.Build
import android.os.IBinder
import android.view.*
import android.widget.ImageView
import com.deepanshu.dkassistant.R

/**
 * Floating Widget Service
 *
 * Displays a persistent floating button overlay that allows:
 * - Always-on access to DK Assistant
 * - Quick voice activation without opening the app
 * - Draggable widget for user convenience
 *
 * Required Permission: SYSTEM_ALERT_WINDOW
 *
 * @author Deepanshu (Boss)
 */
class FloatingWidgetService : Service() {

    private var windowManager: WindowManager? = null
    private var floatingView: View? = null
    private var initialX: Int = 0
    private var initialY: Int = 0
    private var initialTouchX: Float = 0f
    private var initialTouchY: Float = 0f

    override fun onCreate() {
        super.onCreate()
        createFloatingWidget()
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        return START_STICKY
    }

    override fun onBind(intent: Intent?): IBinder? = null

    private fun createFloatingWidget() {
        // Inflate the floating widget layout
        floatingView = LayoutInflater.from(this).inflate(R.layout.floating_widget, null)

        // Set up layout parameters
        val layoutFlag = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            WindowManager.LayoutParams.TYPE_APPLICATION_OVERLAY
        } else {
            @Suppress("DEPRECATION")
            WindowManager.LayoutParams.TYPE_PHONE
        }

        val params = WindowManager.LayoutParams(
            WindowManager.LayoutParams.WRAP_CONTENT,
            WindowManager.LayoutParams.WRAP_CONTENT,
            layoutFlag,
            WindowManager.LayoutParams.FLAG_NOT_FOCUSABLE,
            PixelFormat.TRANSLUCENT
        ).apply {
            gravity = Gravity.TOP or Gravity.START
            x = 0
            y = 100
        }

        // Add view to window manager
        windowManager = getSystemService(WINDOW_SERVICE) as WindowManager
        windowManager?.addView(floatingView, params)

        // Set up click listener
        val widgetButton = floatingView?.findViewById<ImageView>(R.id.widget_button)
        widgetButton?.setOnClickListener {
            onWidgetClicked()
        }

        // Set up touch listener for dragging
        widgetButton?.setOnTouchListener(object : View.OnTouchListener {
            private var isDragging = false
            private var startX = 0f
            private var startY = 0f

            override fun onTouch(v: View?, event: MotionEvent?): Boolean {
                when (event?.action) {
                    MotionEvent.ACTION_DOWN -> {
                        initialX = params.x
                        initialY = params.y
                        initialTouchX = event.rawX
                        initialTouchY = event.rawY
                        startX = event.rawX
                        startY = event.rawY
                        isDragging = false
                        return true
                    }

                    MotionEvent.ACTION_MOVE -> {
                        val deltaX = event.rawX - startX
                        val deltaY = event.rawY - startY

                        if (Math.abs(deltaX) > 10 || Math.abs(deltaY) > 10) {
                            isDragging = true
                        }

                        params.x = initialX + (event.rawX - initialTouchX).toInt()
                        params.y = initialY + (event.rawY - initialTouchY).toInt()

                        windowManager?.updateViewLayout(floatingView, params)
                        return true
                    }

                    MotionEvent.ACTION_UP -> {
                        if (!isDragging) {
                            // This is a click, not a drag
                            v?.performClick()
                        }
                        return true
                    }
                }
                return false
            }
        })
    }

    private fun onWidgetClicked() {
        // Start or activate the DK Assistant Service
        val serviceIntent = Intent(this, DKAssistantService::class.java).apply {
            action = DKAssistantService.ACTION_START_LISTENING
        }

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            startForegroundService(serviceIntent)
        } else {
            startService(serviceIntent)
        }

        // Visual feedback
        val widgetButton = floatingView?.findViewById<ImageView>(R.id.widget_button)
        widgetButton?.animate()
            ?.scaleX(0.8f)
            ?.scaleY(0.8f)
            ?.setDuration(100)
            ?.withEndAction {
                widgetButton.animate()
                    .scaleX(1f)
                    .scaleY(1f)
                    .setDuration(100)
                    .start()
            }
            ?.start()
    }

    override fun onDestroy() {
        super.onDestroy()

        // Remove floating view
        if (floatingView != null) {
            windowManager?.removeView(floatingView)
            floatingView = null
        }
    }
}
