/**
 * js/main.js
 * Tüm sayfalarda ortak kullanılan JavaScript
 * Ali Ayyıldız - b241210069
 */

/* ============================================================
   1. NAVİGASYON – Hamburger Menü
   ============================================================ */
function toggleMenu() {
  const links = document.getElementById('navLinks');
  const ham   = document.getElementById('hamburger');
  if (!links) return;
  links.classList.toggle('open');
  if (ham) ham.classList.toggle('open');
}

// Sayfa dışına tıklanınca kapat
document.addEventListener('click', function(e) {
  const nav   = document.querySelector('.site-nav');
  const links = document.getElementById('navLinks');
  if (links && links.classList.contains('open') && nav && !nav.contains(e.target)) {
    links.classList.remove('open');
  }
});

// Aktif sayfayı işaretle
(function markActive() {
  const path = window.location.pathname.split('/').pop() || 'about.html';
  document.querySelectorAll('.nav-links a').forEach(a => {
    const href = a.getAttribute('href');
    if (href === path) a.classList.add('active');
  });
})();


/* ============================================================
   2. SCROLL – Fade-in animasyonu
   ============================================================ */
(function initFadeIn() {
  const items = document.querySelectorAll('.fade-in');
  if (!items.length) return;

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  items.forEach(el => observer.observe(el));
})();


/* ============================================================
   3. NAVBAR – Scroll rengi
   ============================================================ */
(function navScroll() {
  const nav = document.querySelector('.site-nav');
  if (!nav) return;
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      nav.style.background = 'rgba(13,27,42,0.98)';
      nav.style.boxShadow  = '0 2px 20px rgba(0,0,0,0.4)';
    } else {
      nav.style.background = 'rgba(13,27,42,0.95)';
      nav.style.boxShadow  = 'none';
    }
  });
})();


/* ============================================================
   4. SMOOTH SCROLL – Anchor bağlantıları
   ============================================================ */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', function(e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      e.preventDefault();
      const offset = 80; // navbar yüksekliği
      window.scrollTo({
        top: target.getBoundingClientRect().top + window.scrollY - offset,
        behavior: 'smooth'
      });
    }
  });
});


/* ============================================================
   5. YARDIMCI – Tarih formatı
   ============================================================ */
function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString('tr-TR', { year:'numeric', month:'long', day:'numeric' });
}


/* ============================================================
   6. YARDIMCI – API hata mesajı
   ============================================================ */
function showAPIError(containerId, message) {
  const el = document.getElementById(containerId);
  if (!el) return;
  el.innerHTML = `
    <div class="alert-custom alert-warning" style="margin-top:1rem;">
      ⚠️ ${message}
    </div>`;
}


/* ============================================================
   7. FORM – Input border reset (hata sonrası)
   ============================================================ */
document.querySelectorAll('.form-control-custom').forEach(input => {
  input.addEventListener('input', function() {
    this.style.borderColor = '';
    const errEl = document.getElementById(this.id + 'Error');
    if (errEl) errEl.classList.remove('visible');
  });
});


/* ============================================================
   8. PRINT / PDF Desteği
   ============================================================ */
function printPage() {
  window.print();
}
