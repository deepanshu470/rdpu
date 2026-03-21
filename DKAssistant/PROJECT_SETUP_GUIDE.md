# DK AI Assistant - Project Setup Guide

## Overview
DK is an advanced personal AI Assistant Android app designed for **Deepanshu** (the Boss). It features voice activation, biometric authentication, app control, notification management, hardware control, and AI-powered conversations.

---

## Prerequisites

### Required Tools:
1. **Android Studio** (latest stable version - Arctic Fox or later)
2. **JDK 11 or higher**
3. **Android SDK** with minimum API level 26 (Android 8.0)
4. **Target SDK**: API 34 (Android 14)
5. **Kotlin** version 1.9.0 or higher

### Required API Keys:
1. **OpenAI API Key** OR **Claude API Key** for LLM integration
   - Sign up at https://platform.openai.com/ or https://console.anthropic.com/
   - Store your API key securely

---

## Step-by-Step Project Setup

### Step 1: Import Project
1. Open **Android Studio**
2. Select **File > Open**
3. Navigate to the `DKAssistant` directory
4. Click **OK** to import the project
5. Wait for Gradle sync to complete

### Step 2: Configure API Keys
1. Create a `local.properties` file in the project root if it doesn't exist
2. Add your API key:
   ```properties
   sdk.dir=/path/to/your/android/sdk
   OPENAI_API_KEY=your_openai_api_key_here
   # OR
   CLAUDE_API_KEY=your_claude_api_key_here
   ```

### Step 3: Update Build Configuration
1. Open `app/build.gradle`
2. Ensure all dependencies are synced
3. Build the project: **Build > Make Project**

### Step 4: Enable Required Permissions on Device

#### Before Running the App:
The app requires several sensitive permissions that must be granted manually:

1. **Notification Access**
   - Go to: Settings > Apps > DK Assistant > Notifications
   - Enable "Allow notification access"

2. **Accessibility Service** (for notification reading)
   - Go to: Settings > Accessibility
   - Find "DK Assistant" and enable it

3. **Display Over Other Apps** (for floating widget)
   - Go to: Settings > Apps > Special app access > Display over other apps
   - Find "DK Assistant" and enable

4. **Microphone Permission**
   - Will be requested at runtime when starting voice service

5. **Camera Permission** (for flashlight)
   - Will be requested at runtime

6. **Biometric Authentication**
   - Ensure your device has fingerprint/face unlock configured
   - Go to: Settings > Security > Biometrics

### Step 5: Run the Application
1. Connect your Android device via USB with **USB Debugging enabled**
2. Or use an **Android Emulator** (API 26+)
3. Click **Run** (green play button) in Android Studio
4. Select your target device
5. Wait for installation and launch

---

## Testing the Features

### 1. Voice Wake-up Test
- Say "DK" or "Hey DK" when the floating widget is visible
- The assistant should activate and listen for commands

### 2. Authentication Test
- Try to perform a sensitive command
- Biometric prompt should appear
- If biometric fails, fallback to PIN (default: 123456)

### 3. App Control Test
Voice commands to test:
- "DK, open WhatsApp"
- "DK, open Instagram"
- "DK, open Chrome"

### 4. Notification Management Test
- Send yourself a WhatsApp message
- DK should read it aloud
- Say "Reply with [your message]" to respond

### 5. Hardware Control Test
- "DK, turn on flashlight"
- "DK, turn off flashlight"

### 6. AI Conversation Test
- "DK, what is the capital of France?"
- "DK, tell me about quantum computing"

---

## Architecture Overview

### Core Components:

#### 1. **DKAssistantService** (Foreground Service)
- Handles continuous voice listening
- Processes voice commands
- Manages Text-to-Speech responses
- Location: `app/src/main/java/com/deepanshu/dkassistant/service/DKAssistantService.kt`

#### 2. **NotificationReaderService** (NotificationListenerService)
- Intercepts incoming notifications
- Reads WhatsApp messages aloud
- Enables voice replies via RemoteInput
- Location: `app/src/main/java/com/deepanshu/dkassistant/service/NotificationReaderService.kt`

#### 3. **FloatingWidgetService** (Overlay Service)
- Displays persistent floating button
- Always-on voice activation
- Location: `app/src/main/java/com/deepanshu/dkassistant/service/FloatingWidgetService.kt`

#### 4. **BiometricAuthManager**
- Handles fingerprint/face authentication
- Fallback PIN verification
- Location: `app/src/main/java/com/deepanshu/dkassistant/manager/BiometricAuthManager.kt`

#### 5. **AIBrainManager**
- Integrates with OpenAI/Claude API
- Handles natural language processing
- Manages conversation context
- Location: `app/src/main/java/com/deepanshu/dkassistant/manager/AIBrainManager.kt`

#### 6. **MainActivity** (Dashboard)
- Dark-themed futuristic UI
- Permission management toggles
- Command history view
- Location: `app/src/main/java/com/deepanshu/dkassistant/ui/MainActivity.kt`

---

## Permissions Explained

### AndroidManifest.xml Permissions:

```xml
<!-- Voice Recognition -->
<uses-permission android:name="android.permission.RECORD_AUDIO" />

<!-- Notification Management -->
<uses-permission android:name="android.permission.BIND_NOTIFICATION_LISTENER_SERVICE" />

<!-- Floating Widget -->
<uses-permission android:name="android.permission.SYSTEM_ALERT_WINDOW" />

<!-- Hardware Control -->
<uses-permission android:name="android.permission.CAMERA" />
<uses-permission android:name="android.permission.FLASHLIGHT" />

<!-- Foreground Service -->
<uses-permission android:name="android.permission.FOREGROUND_SERVICE" />
<uses-permission android:name="android.permission.FOREGROUND_SERVICE_MICROPHONE" />

<!-- Biometric -->
<uses-permission android:name="android.permission.USE_BIOMETRIC" />

<!-- Internet for AI API -->
<uses-permission android:name="android.permission.INTERNET" />

<!-- Wake Lock for persistent listening -->
<uses-permission android:name="android.permission.WAKE_LOCK" />
```

---

## Customization

### Change Owner Name:
Edit `app/src/main/res/values/strings.xml`:
```xml
<string name="owner_name">Deepanshu</string>
```

### Change Wake Word:
Edit `DKAssistantService.kt` line ~150:
```kotlin
private val wakeWords = listOf("dk", "hey dk", "deepanshu")
```

### Change Default PIN:
Edit `BiometricAuthManager.kt` line ~200:
```kotlin
private const val DEFAULT_PIN = "123456"
```

### Update AI API:
Edit `AIBrainManager.kt` to switch between OpenAI and Claude:
```kotlin
private const val USE_OPENAI = true // Set to false for Claude
```

---

## Troubleshooting

### Issue: Voice recognition not working
**Solution**:
- Check microphone permission is granted
- Ensure device is not in silent/vibrate mode
- Verify Google app is installed (required for SpeechRecognizer)

### Issue: Notifications not being read
**Solution**:
- Enable Notification Listener permission in Settings
- Restart the app after enabling
- Check WhatsApp notifications are not muted

### Issue: Floating widget not visible
**Solution**:
- Enable "Display over other apps" permission
- Restart the FloatingWidgetService
- Check if battery optimization is disabled for the app

### Issue: Biometric authentication failing
**Solution**:
- Verify device has biometric enrolled
- Use fallback PIN: 123456
- Check BiometricPrompt API is supported (API 28+)

### Issue: AI responses not working
**Solution**:
- Verify API key is correctly set in local.properties
- Check internet connection
- Review Logcat for API errors
- Ensure you have API credits/quota available

---

## Building for Production

### 1. Generate Signed APK:
```bash
# In Android Studio:
Build > Generate Signed Bundle/APK > APK
```

### 2. Security Best Practices:
- Store API keys in BuildConfig, not hardcoded
- Enable ProGuard/R8 code obfuscation
- Use encrypted SharedPreferences for sensitive data
- Implement certificate pinning for API calls

### 3. Release Checklist:
- [ ] Update version code and version name
- [ ] Test on multiple Android versions (8.0 - 14)
- [ ] Test with different biometric types
- [ ] Verify all permissions work correctly
- [ ] Test battery optimization scenarios
- [ ] Review security vulnerabilities
- [ ] Update API keys for production

---

## Project Structure
```
DKAssistant/
├── app/
│   ├── src/
│   │   ├── main/
│   │   │   ├── java/com/deepanshu/dkassistant/
│   │   │   │   ├── service/
│   │   │   │   │   ├── DKAssistantService.kt          # Main voice service
│   │   │   │   │   ├── NotificationReaderService.kt   # Notification listener
│   │   │   │   │   └── FloatingWidgetService.kt       # Floating widget
│   │   │   │   ├── ui/
│   │   │   │   │   └── MainActivity.kt                # Dashboard UI
│   │   │   │   ├── manager/
│   │   │   │   │   ├── BiometricAuthManager.kt        # Authentication
│   │   │   │   │   └── AIBrainManager.kt              # AI integration
│   │   │   │   └── utils/
│   │   │   │       └── PermissionHelper.kt            # Permission utilities
│   │   │   ├── res/
│   │   │   │   ├── layout/
│   │   │   │   │   ├── activity_main.xml              # Dashboard layout
│   │   │   │   │   └── floating_widget.xml            # Widget layout
│   │   │   │   ├── values/
│   │   │   │   │   ├── strings.xml
│   │   │   │   │   ├── colors.xml
│   │   │   │   │   └── themes.xml
│   │   │   │   └── drawable/
│   │   │   └── AndroidManifest.xml                    # Permissions & Services
│   │   └── test/
│   ├── build.gradle                                    # App dependencies
│   └── proguard-rules.pro
├── gradle/
├── build.gradle                                        # Project config
├── settings.gradle
└── PROJECT_SETUP_GUIDE.md                             # This file
```

---

## Support & Contact
For issues or customization requests, contact the app owner: **Deepanshu**

**Version**: 1.0.0
**Last Updated**: March 2026
**Minimum Android Version**: 8.0 (API 26)
**Target Android Version**: 14 (API 34)
