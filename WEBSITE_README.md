# 🥒 Achar Bazaar - E-Commerce Website for Pickles

A beautiful, animated e-commerce website for selling pickles (achar) built with HTML, CSS, and JavaScript.

## 🌟 Features

### Design & Animations
- **Smooth Animations**: Multiple CSS animations including fade-in, slide-in, bounce, float, and rotate effects
- **Responsive Design**: Works perfectly on all devices (desktop, tablet, mobile)
- **Modern UI**: Clean and colorful interface with gradient backgrounds
- **Interactive Elements**: Hover effects, smooth transitions, and animated components

### E-Commerce Functionality
- **Product Catalog**: Display of 8 different types of pickles with images, descriptions, and prices
- **Shopping Cart**: Full-featured cart with add/remove items, quantity management
- **Local Storage**: Cart data persists across browser sessions
- **Real-time Updates**: Cart count and total price update instantly
- **Smooth Checkout**: Simple checkout process with order confirmation

### Sections
1. **Hero Section**: Eye-catching landing with animated floating pickles
2. **Features**: 4 key benefits displayed in animated cards
3. **Products**: Grid of 8 pickle products with add-to-cart functionality
4. **About**: Information about the business
5. **Contact**: Contact form for customer inquiries

### Interactive Features
- Animated navigation bar with scroll effects
- Smooth scrolling between sections
- Shopping cart sidebar that slides in/out
- Toast notifications for user actions
- Parallax scrolling effects
- Keyboard support (ESC to close cart)

## 🎨 Products Available

1. **आम का अचार** (Mango Pickle) - ₹199
2. **मिर्च का अचार** (Chilli Pickle) - ₹149
3. **नींबू का अचार** (Lemon Pickle) - ₹179
4. **गाजर का अचार** (Carrot Pickle) - ₹159
5. **मिक्स अचार** (Mixed Pickle) - ₹189
6. **लहसुन का अचार** (Garlic Pickle) - ₹169
7. **अदरक का अचार** (Ginger Pickle) - ₹139
8. **करेला का अचार** (Bitter Gourd Pickle) - ₹129

## 🚀 How to Use

### Option 1: Open Directly
Simply open `index.html` in any modern web browser (Chrome, Firefox, Safari, Edge).

### Option 2: Use Live Server
1. Install a local server (e.g., Live Server extension for VS Code)
2. Right-click on `index.html` and select "Open with Live Server"
3. The website will open in your default browser

### Option 3: Python HTTP Server
```bash
# Python 3
python -m http.server 8000

# Python 2
python -m SimpleHTTPServer 8000
```
Then open `http://localhost:8000` in your browser.

## 📁 File Structure

```
rdpu/
├── index.html          # Main HTML file
├── styles.css          # CSS styles and animations
├── script.js           # JavaScript functionality
└── WEBSITE_README.md   # This file
```

## 🎯 Features Breakdown

### HTML (index.html)
- Semantic HTML5 structure
- Navigation bar with logo and cart icon
- Hero section with call-to-action
- Features section with 4 benefit cards
- Products section (dynamically populated)
- About section
- Contact form
- Shopping cart sidebar
- Footer

### CSS (styles.css)
- CSS Variables for easy theme customization
- Flexbox and Grid layouts
- Multiple animations:
  - slideDown, fadeIn, bounceIn, pulse
  - float, rotate, scaleIn, slideInUp
- Responsive media queries
- Smooth transitions on all interactive elements
- Gradient backgrounds
- Custom scrollbar styling

### JavaScript (script.js)
- Product data management
- Shopping cart functionality
- Add/remove/update cart items
- LocalStorage integration
- Toast notifications
- Smooth scroll navigation
- Parallax effects
- Intersection Observer for scroll animations
- Form submission handling

## 🛒 Shopping Cart Features

- **Add to Cart**: Click "Add to Cart" on any product
- **View Cart**: Click the cart icon in navigation
- **Update Quantity**: Use +/- buttons in cart
- **Remove Items**: Click "Remove" button
- **Persistent Cart**: Cart data saves in browser
- **Checkout**: Review and place order
- **Close Cart**: Click X button, overlay, or press ESC

## 🎨 Customization

### Change Colors
Edit CSS variables in `styles.css`:
```css
:root {
    --primary-color: #ff6b6b;
    --secondary-color: #4ecdc4;
    --accent-color: #ffe66d;
    --dark-color: #2d3436;
    --light-color: #f8f9fa;
}
```

### Add More Products
Edit the products array in `script.js`:
```javascript
const products = [
    {
        id: 9,
        name: "Your Pickle Name",
        emoji: "🥒",
        description: "Description here",
        price: 199
    }
];
```

## 🌐 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## 📱 Mobile Responsive

The website is fully responsive with:
- Mobile-first design approach
- Touch-friendly buttons and navigation
- Optimized images and animations
- Full-width cart on mobile devices

## 🎭 Animations Used

1. **Navigation**: Slide down animation on page load
2. **Hero**: Fade in and bounce animations
3. **Features**: Stagger fade-in effect
4. **Products**: Scale-in animation on scroll
5. **Floating Pickles**: Continuous floating animation
6. **Cart**: Slide-in from right side
7. **Buttons**: Hover and active state animations
8. **Toast**: Slide up notification

## 💡 Tips for Users

1. Products are added to cart with one click
2. Cart persists even after closing browser
3. Use smooth scrolling navigation links
4. Mobile users can swipe to close cart
5. All animations are GPU-accelerated for smooth performance

## 🔧 Technical Details

- **No Dependencies**: Pure HTML, CSS, and JavaScript (no frameworks)
- **Modern ES6+**: Uses modern JavaScript features
- **CSS Grid & Flexbox**: For flexible layouts
- **LocalStorage API**: For cart persistence
- **Intersection Observer**: For scroll animations
- **Custom Properties**: For theme management

## 🎉 Credits

Created with ❤️ for pickle lovers everywhere!

## 📄 License

This project is open source and available for personal and commercial use.

---

**Enjoy shopping at Achar Bazaar! 🥒**
