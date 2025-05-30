// assets/js/dashboard.js
document.addEventListener('DOMContentLoaded', () => {
  const btn     = document.getElementById('topnav-hamburger-icon');
  const overlay = document.querySelector('.vertical-overlay');

  if (!btn) return;

  btn.addEventListener('click', () => {
    // body’ye tek sınıf ekle/çıkart
    document.body.classList.toggle('sidebar-collapsed');
    // overlay varsa göster/gizle
    if (overlay) overlay.classList.toggle('show');
  });

  if (overlay) {
    overlay.addEventListener('click', () => {
      document.body.classList.remove('sidebar-collapsed');
      overlay.classList.remove('show');
    });
  }
});
document.addEventListener('DOMContentLoaded', () => {
  const closeBtn = document.getElementById('sidebar-close-btn');
  const toggleBtn = document.getElementById('topnav-hamburger-icon');

  function collapseSidebar() {
    document.body.classList.add('sidebar-collapsed');
    toggleBtn.classList.remove('open');
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', collapseSidebar);
  }
  // Mevcut overlay tıklama kodun zaten collapseSidebar yapıyor.
});
