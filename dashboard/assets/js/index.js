document.addEventListener('DOMContentLoaded', () => {
  const btn        = document.getElementById('topnav-hamburger-icon');
  const overlay    = document.querySelector('.vertical-overlay');
  const closeBtn   = document.getElementById('sidebar-close-btn');
  const body       = document.body;
  const STORAGE_KEY = 'sidebarCollapsed'; 

  const collapsedState = localStorage.getItem(STORAGE_KEY);
  if (collapsedState === 'true') {
    body.classList.add('sidebar-collapsed');
    if (overlay) overlay.classList.remove('show');
    if (btn) btn.classList.remove('open');
  } else {
    body.classList.remove('sidebar-collapsed');
    if (overlay) overlay.classList.remove('show');
    if (btn) btn.classList.add('open');
  }

  if (btn) {
    btn.addEventListener('click', () => {
      const isCollapsed = body.classList.contains('sidebar-collapsed');
      if (isCollapsed) {
        body.classList.remove('sidebar-collapsed');
        if (overlay) overlay.classList.remove('show');
        btn.classList.add('open');
      } else {
        body.classList.add('sidebar-collapsed');
        if (overlay) overlay.classList.remove('show');
        btn.classList.remove('open');
      }
      const newState = body.classList.contains('sidebar-collapsed') ? 'true' : 'false';
      localStorage.setItem(STORAGE_KEY, newState);
    });
  }

  if (overlay) {
    overlay.addEventListener('click', () => {
      body.classList.remove('sidebar-collapsed');
      overlay.classList.remove('show');
      if (btn) btn.classList.add('open');
      localStorage.setItem(STORAGE_KEY, 'false');
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      body.classList.add('sidebar-collapsed');
      if (overlay) overlay.classList.remove('show');
      if (btn) btn.classList.remove('open');
      localStorage.setItem(STORAGE_KEY, 'true');
    });
  }
});
