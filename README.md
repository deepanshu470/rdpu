# 🥒 Achar - India's Finest Homemade Pickles

A complete e-commerce website for selling authentic Indian pickles (achar), built with PHP using the MVC pattern.

## Features
- 🛒 Full shopping cart with session management
- 🏷️ Coupon/discount code system
- 📦 Order placement and management
- 👨‍💼 Admin panel for product/order/offer management
- 📱 Fully responsive design (mobile-first)
- 🎨 Beautiful UI with Tailwind CSS + Bootstrap 5
- ⚡ AJAX cart operations
- 🔔 Toast notification system

## Tech Stack
- **Backend**: PHP 7.4+ (no framework)
- **Architecture**: MVC Pattern
- **Storage**: JSON files (no database required)
- **Frontend**: Tailwind CSS, Bootstrap 5, Alpine.js, Font Awesome
- **Sessions**: PHP native sessions for cart & admin auth

## Setup

### Requirements
- PHP 7.4 or higher

### Quick Start
```bash
git clone <repo-url>
cd rdpu
php -S localhost:8000
```

Open http://localhost:8000 in your browser.

## Admin Panel
- **URL**: http://localhost:8000/?page=admin
- **Username**: admin
- **Password**: admin123

## Directory Structure
```
rdpu/
├── index.php          # Router / entry point
├── config/            # App configuration
├── data/              # JSON data storage
├── models/            # Data models
├── controllers/       # Business logic
├── views/             # HTML templates
└── public/            # Static assets (CSS, JS, images)
```

## Product Categories
- 🥭 Mango (Aam ka Achar)
- 🍋 Lemon (Nimbu ka Achar)
- 🧄 Garlic (Lehsun ka Achar)
- 🥗 Mixed Vegetable
- 🌶️ Green Chili
- 🥕 Carrot

## License
MIT
