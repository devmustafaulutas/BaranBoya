document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const twoFaCode = document.getElementById('2fa_code') ? document.getElementById('2fa_code').value : null;
        if (!username || !password || (twoFaCode !== null && !twoFaCode)) {
            event.preventDefault();
            alert('Lütfen gerekli tüm alanları doldurun.');
        }
    });
});