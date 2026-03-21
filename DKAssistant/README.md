# DK Assistant - Android AI Personal Assistant

An advanced personal AI Assistant Android app exclusively for Deepanshu (the Boss).

## Features

- 🎤 **Voice Wake-up**: Always-on "DK" wake word detection
- 🔐 **Biometric Authentication**: Fingerprint/Face unlock with PIN fallback
- 📱 **App Control**: Launch apps via voice commands
- 📬 **Smart Notifications**: Read and reply to WhatsApp messages
- 🔦 **Hardware Control**: Voice-controlled flashlight
- 🤖 **AI Brain**: OpenAI/Claude integration for intelligent conversations
- 🎨 **Futuristic UI**: Dark-themed dashboard with permission management

## Setup

See [PROJECT_SETUP_GUIDE.md](PROJECT_SETUP_GUIDE.md) for detailed setup instructions.

## Quick Start

1. Import project in Android Studio
2. Add API key to `local.properties`
3. Build and run on device (API 26+)
4. Grant required permissions
5. Say "DK" to activate!

## Architecture

- **DKAssistantService**: Foreground service for voice listening
- **NotificationReaderService**: Handles notification reading/replies
- **FloatingWidgetService**: Persistent overlay button
- **BiometricAuthManager**: Owner authentication
- **AIBrainManager**: LLM API integration

## Requirements

- Android 8.0+ (API 26)
- Microphone permission
- Camera permission (flashlight)
- Notification listener access
- Display over other apps permission

## Owner

Built exclusively for **Deepanshu**

## Version

1.0.0 - March 2026
