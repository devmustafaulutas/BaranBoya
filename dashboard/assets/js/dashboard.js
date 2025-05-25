// assets/js/dashboard.js
(function(){
    const KEY  = 'layoutMode';
    const html = document.documentElement;
  
    // ➊ Sayfa yüklenir yüklenmez saklı modu uygula
    const saved = localStorage.getItem(KEY) || 'light';
    html.setAttribute('data-layout-mode', saved);
  
    // ➋ DOM iyice hazır olunca butonu dinle
    window.addEventListener('DOMContentLoaded', ()=>{
      const btn  = document.querySelector('.light-dark-mode');
      if (!btn) return;
      const icon = btn.querySelector('i');
  
      // Başlangıçta ikonu güncelle
      if (saved === 'dark') {
        icon.classList.remove('bx-moon');
        icon.classList.add('bx-sun');
      } else {
        icon.classList.remove('bx-sun');
        icon.classList.add('bx-moon');
      }
  
      // Tıklanınca modu değiştir, sakla ve ikonu değiştir
      btn.addEventListener('click', ()=>{
        const cur  = html.getAttribute('data-layout-mode');
        const next = cur === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-layout-mode', next);
        localStorage.setItem(KEY, next);
  
        if (next === 'dark') {
          icon.classList.replace('bx-moon','bx-sun');
        } else {
          icon.classList.replace('bx-sun','bx-moon');
        }
      });
    });
  })();
  