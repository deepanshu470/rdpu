// Toast Notification System
function showToast(message, type = 'success') {
  const container = document.getElementById('toast-container');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = `toast-notification ${type === 'error' ? 'error' : ''}`;
  toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => toast.classList.add('show'), 50);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400);
  }, 3500);
}

// Add to Cart AJAX
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.add-to-cart-btn');
  if (!btn) return;
  e.preventDefault();
  const productId = btn.dataset.id;
  const qtyInput = document.querySelector(`#qty-${productId}`);
  const quantity = qtyInput ? qtyInput.value : 1;
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
  const formData = new FormData();
  formData.append('product_id', productId);
  formData.append('quantity', quantity);
  formData.append('ajax', '1');
  fetch('?page=cart&action=add', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast('Added to cart! 🛒');
        document.querySelectorAll('.cart-badge').forEach(b => {
          b.textContent = data.cartCount;
          b.style.display = data.cartCount > 0 ? 'flex' : 'none';
        });
      } else {
        showToast(data.message || 'Error adding to cart', 'error');
      }
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
    })
    .catch(() => {
      showToast('Network error. Please try again.', 'error');
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
    });
});

// Scroll Reveal
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('active');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// FAQ Accordion
document.querySelectorAll('.faq-question').forEach(btn => {
  btn.addEventListener('click', function() {
    const answer = this.nextElementSibling;
    const icon = this.querySelector('.faq-icon');
    const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
    document.querySelectorAll('.faq-answer').forEach(a => a.style.maxHeight = null);
    document.querySelectorAll('.faq-icon').forEach(i => i.style.transform = 'rotate(0deg)');
    if (!isOpen) {
      answer.style.maxHeight = answer.scrollHeight + 'px';
      if (icon) icon.style.transform = 'rotate(180deg)';
    }
  });
});

// Coupon validation
const VALID_COUPONS = { 'PICKLE10': 10, 'SAVE15': 15, 'FIRST20': 20, 'FESTIVAL25': 25 };
function validateCoupon(code) {
  const upper = code.toUpperCase().trim();
  if (VALID_COUPONS[upper]) {
    showToast(`🎉 Coupon applied! ${VALID_COUPONS[upper]}% off`, 'success');
    return VALID_COUPONS[upper];
  } else {
    showToast('Invalid coupon code', 'error');
    return 0;
  }
}
const couponBtn = document.getElementById('apply-coupon');
if (couponBtn) {
  couponBtn.addEventListener('click', function() {
    const code = document.getElementById('coupon-input')?.value || '';
    if (code.trim()) {
      validateCoupon(code);
    } else {
      showToast('Please enter a coupon code', 'error');
    }
  });
}

// Quantity buttons
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('qty-minus')) {
    const input = e.target.nextElementSibling;
    if (input && parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
  }
  if (e.target.classList.contains('qty-plus')) {
    const input = e.target.previousElementSibling;
    if (input) input.value = parseInt(input.value || 0) + 1;
  }
});

// Back to Top
const backToTop = document.getElementById('back-to-top');
if (backToTop) {
  window.addEventListener('scroll', () => {
    backToTop.style.opacity = window.scrollY > 400 ? '1' : '0';
    backToTop.style.pointerEvents = window.scrollY > 400 ? 'all' : 'none';
  });
  backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// Re-run reveal on load
window.addEventListener('load', () => {
  document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
});

// Payment method border highlight
document.querySelectorAll('input[name="payment"]').forEach(radio => {
  radio.addEventListener('change', function() {
    document.querySelectorAll('input[name="payment"]').forEach(r => {
      const label = r.closest('label');
      if (label) label.style.borderColor = r.checked ? '#d97706' : '#e5e7eb';
    });
  });
});
