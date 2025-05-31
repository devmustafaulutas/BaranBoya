document.addEventListener('DOMContentLoaded', () => {
    // Animasyonları tetiklemek için IntersectionObserver kullanımı
    const animateOnScrollElements = document.querySelectorAll('.index-feature-card, .index-product-card, .fade-in');

    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('index-feature-card') || entry.target.classList.contains('index-product-card')) {
                    entry.target.classList.add('animate');
                }
                if (entry.target.classList.contains('fade-in')) {
                    entry.target.classList.add('visible');
                }
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animateOnScrollElements.forEach(element => {
        observer.observe(element);
    });

    const counters = document.querySelectorAll('.stat-counter');
    counters.forEach(counter => {
        let target = +counter.getAttribute('data-target');
        let count = 0;
        const increment = target / 200;

        const updateCounter = () => {
            count += increment;
            if (count < target) {
                counter.innerText = Math.ceil(count);
                requestAnimationFrame(updateCounter);
            } else {
                counter.innerText = target;
            }
        };

        updateCounter();
    });
});
document.addEventListener("DOMContentLoaded", function () {
    var toastElList = [].slice.call(
        document.querySelectorAll("#toast-container .toast")
    );
    toastElList.forEach(function (toastEl) {
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
});