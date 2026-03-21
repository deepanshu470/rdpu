// Product Data
const products = [
    {
        id: 1,
        name: "आम का अचार",
        emoji: "🥭",
        description: "स्वादिष्ट कच्चे आम का अचार",
        price: 199
    },
    {
        id: 2,
        name: "मिर्च का अचार",
        emoji: "🌶️",
        description: "तीखा और मसालेदार मिर्च का अचार",
        price: 149
    },
    {
        id: 3,
        name: "नींबू का अचार",
        emoji: "🍋",
        description: "खट्टा-मीठा नींबू का अचार",
        price: 179
    },
    {
        id: 4,
        name: "गाजर का अचार",
        emoji: "🥕",
        description: "कुरकुरा गाजर का अचार",
        price: 159
    },
    {
        id: 5,
        name: "मिक्स अचार",
        emoji: "🥒",
        description: "विभिन्न सब्जियों का मिश्रण",
        price: 189
    },
    {
        id: 6,
        name: "लहसुन का अचार",
        emoji: "🧄",
        description: "स्वास्थ्यवर्धक लहसुन का अचार",
        price: 169
    },
    {
        id: 7,
        name: "अदरक का अचार",
        emoji: "🫚",
        description: "तीखा अदरक का अचार",
        price: 139
    },
    {
        id: 8,
        name: "करेला का अचार",
        emoji: "🥒",
        description: "पौष्टिक करेले का अचार",
        price: 129
    }
];

// Shopping Cart
let cart = [];

// Initialize the website
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
    loadCart();
    setupEventListeners();
    animateOnScroll();
});

// Load Products
function loadProducts() {
    const productGrid = document.getElementById('productGrid');

    products.forEach((product, index) => {
        const productCard = createProductCard(product);
        productCard.style.animationDelay = `${index * 0.1}s`;
        productGrid.appendChild(productCard);
    });
}

// Create Product Card
function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
        <div class="product-image">${product.emoji}</div>
        <div class="product-info">
            <h3 class="product-name">${product.name}</h3>
            <p class="product-description">${product.description}</p>
            <div class="product-footer">
                <span class="product-price">₹${product.price}</span>
                <button class="add-to-cart" onclick="addToCart(${product.id})">
                    Add to Cart
                </button>
            </div>
        </div>
    `;
    return card;
}

// Add to Cart
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            ...product,
            quantity: 1
        });
    }

    updateCart();
    showToast('Product added to cart! 🎉');
    animateCartIcon();
}

// Update Cart
function updateCart() {
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const cartTotal = document.getElementById('cartTotal');

    // Update cart count
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;

    // Update cart items
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <p>Your cart is empty</p>
            </div>
        `;
    } else {
        cartItems.innerHTML = '';
        cart.forEach(item => {
            const cartItem = createCartItem(item);
            cartItems.appendChild(cartItem);
        });
    }

    // Update total
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    cartTotal.textContent = `₹${total}`;

    // Save to localStorage
    saveCart();
}

// Create Cart Item
function createCartItem(item) {
    const div = document.createElement('div');
    div.className = 'cart-item';
    div.innerHTML = `
        <div class="cart-item-image">${item.emoji}</div>
        <div class="cart-item-details">
            <div class="cart-item-name">${item.name}</div>
            <div class="cart-item-price">₹${item.price} each</div>
            <div class="cart-item-quantity">
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                <span>${item.quantity}</span>
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                <button class="remove-item" onclick="removeFromCart(${item.id})">Remove</button>
            </div>
        </div>
    `;
    return div;
}

// Update Quantity
function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            updateCart();
        }
    }
}

// Remove from Cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCart();
    showToast('Product removed from cart');
}

// Toggle Cart
function toggleCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');

    cartSidebar.classList.toggle('active');
    cartOverlay.classList.toggle('active');

    // Prevent body scroll when cart is open
    if (cartSidebar.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
}

// Checkout
function checkout() {
    if (cart.length === 0) {
        showToast('Your cart is empty!');
        return;
    }

    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const itemsList = cart.map(item => `${item.name} x${item.quantity}`).join(', ');

    alert(`Thank you for your order! 🎉\n\nItems: ${itemsList}\nTotal: ₹${total}\n\nYour order will be delivered soon!`);

    cart = [];
    updateCart();
    toggleCart();
}

// Save Cart to localStorage
function saveCart() {
    localStorage.setItem('pickleCart', JSON.stringify(cart));
}

// Load Cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('pickleCart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCart();
    }
}

// Show Toast Notification
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideInUp 0.3s ease-out reverse';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 2000);
}

// Animate Cart Icon
function animateCartIcon() {
    const cartIcon = document.querySelector('.cart-icon');
    cartIcon.style.animation = 'none';
    setTimeout(() => {
        cartIcon.style.animation = 'pulse 0.5s ease';
    }, 10);
}

// Setup Event Listeners
function setupEventListeners() {
    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Contact Form
    const contactForm = document.getElementById('contactForm');
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('Thank you for your message! We will contact you soon. 📧');
        contactForm.reset();
    });

    // Close cart on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const cartSidebar = document.getElementById('cartSidebar');
            if (cartSidebar.classList.contains('active')) {
                toggleCart();
            }
        }
    });
}

// Animate on Scroll
function animateOnScroll() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeIn 0.8s ease-out forwards';
            }
        });
    }, {
        threshold: 0.1
    });

    // Observe elements
    document.querySelectorAll('.feature-card, .product-card, .about-content').forEach(el => {
        observer.observe(el);
    });
}

// Navbar scroll effect
let lastScroll = 0;
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    const currentScroll = window.pageYOffset;

    if (currentScroll > lastScroll && currentScroll > 100) {
        navbar.style.transform = 'translateY(-100%)';
    } else {
        navbar.style.transform = 'translateY(0)';
    }

    lastScroll = currentScroll;
});

// Add parallax effect to hero
window.addEventListener('scroll', () => {
    const hero = document.querySelector('.hero');
    const scrolled = window.pageYOffset;
    if (hero) {
        hero.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// Random floating animation for pickles
function randomFloatAnimation() {
    const pickles = document.querySelectorAll('.floating-pickle');
    pickles.forEach(pickle => {
        const randomX = Math.random() * 100 - 50;
        const randomY = Math.random() * 100 - 50;
        const randomRotate = Math.random() * 360;

        setInterval(() => {
            pickle.style.transform = `translate(${randomX}px, ${randomY}px) rotate(${randomRotate}deg)`;
        }, 3000);
    });
}

randomFloatAnimation();

// Console message
console.log('%c🥒 Welcome to Achar Bazaar! 🥒', 'color: #ff6b6b; font-size: 24px; font-weight: bold;');
console.log('%cEnjoy shopping for delicious pickles!', 'color: #4ecdc4; font-size: 16px;');
