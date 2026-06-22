// SimuLearn360 — shared front-end behaviour

// ── Password visibility toggle ──────────────────────────────
function togglePwd() {
  const input = document.getElementById('pwdInput');
  const icon  = document.getElementById('pwdToggle');
  if (!input) return;
  const show = input.type === 'password';
  input.type = show ? 'text' : 'password';
  if (icon) {
    icon.classList.toggle('fa-eye', !show);
    icon.classList.toggle('fa-eye-slash', show);
  }
}

// ── Language dropdown (auth pages) ──────────────────────────
function toggleLang() {
  const dropdown = document.getElementById('langDropdown');
  if (dropdown) dropdown.classList.toggle('open');
}
document.addEventListener('click', function (e) {
  const dropdown = document.getElementById('langDropdown');
  const btn = document.querySelector('.auth-lang-btn');
  if (dropdown && !dropdown.contains(e.target) && e.target !== btn) {
    dropdown.classList.remove('open');
  }
});

// ── Auth background slideshow (index.php / login screens) ──
(function () {
  const slides = document.querySelectorAll('.auth-bg-slide');
  const dots   = document.querySelectorAll('.auth-bg-dot');
  if (!slides.length) return;
  let current = 0;
  setInterval(function () {
    slides[current].classList.remove('active');
    if (dots[current]) dots[current].classList.remove('active');
    current = (current + 1) % slides.length;
    slides[current].classList.add('active');
    if (dots[current]) dots[current].classList.add('active');
  }, 5000);
})();

// ── Toast notifications ──────────────────────────────────────
function showToast(message, type = 'info') {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const icons = { info: 'fa-circle-info', success: 'fa-circle-check', danger: 'fa-circle-exclamation' };
  const toast = document.createElement('div');
  toast.className = 'toast toast-' + type;
  toast.innerHTML = '<i class="fa ' + (icons[type] || icons.info) + ' icon"></i><span>' + message + '</span>';
  container.appendChild(toast);
  setTimeout(function () { toast.remove(); }, 4000);
}

// ── Sidebar search "/" shortcut ──────────────────────────────
document.addEventListener('keydown', function (e) {
  if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
    const search = document.querySelector('.top-nav-search input');
    if (search) { e.preventDefault(); search.focus(); }
  }
});
