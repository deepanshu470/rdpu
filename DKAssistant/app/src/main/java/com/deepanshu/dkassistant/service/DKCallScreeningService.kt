package com.deepanshu.dkassistant.service

import android.content.Context
import android.os.Build
import android.telecom.Call
import android.telecom.CallScreeningService
import android.telecom.CallScreeningService.CallResponse
import android.util.Log
import androidx.annotation.RequiresApi

/**
 * Call Screening Service
 *
 * Handles incoming calls:
 * - Answers calls automatically
 * - Asks for caller's name
 * - Introduces as Deepanshu's assistant
 * - Can transfer call to Boss or take messages
 *
 * @author Deepanshu (Boss)
 */
@RequiresApi(Build.VERSION_CODES.N)
class DKCallScreeningService : CallScreeningService() {

    companion object {
        private const val TAG = "DKCallScreening"
    }

    override fun onScreenCall(callDetails: Call.Details) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.N) {
            // Allow all calls - we'll handle them in CallHandlerService
            val response = CallResponse.Builder()
                .setDisallowCall(false)
                .setRejectCall(false)
                .setSkipCallLog(false)
                .setSkipNotification(false)
                .build()

            respondToCall(callDetails, response)

            // Notify CallHandlerService about incoming call
            val callerNumber = callDetails.handle?.schemeSpecificPart
            Log.d(TAG, "Incoming call from: $callerNumber")
        }
    }
}
