document.addEventListener('DOMContentLoaded', () => {
    // Animasyonları tetiklemek için IntersectionObserver kullanımı
    const sectorAnimateElements = document.querySelectorAll('.index-sektor-item, .fade-in');

    const observerOptions = {
        threshold: 0.1
    };

    const sectorObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                if(entry.target.classList.contains('index-sektor-item')){
                    entry.target.classList.add('animate');
                }
                if(entry.target.classList.contains('fade-in')){
                    entry.target.classList.add('visible');
                }
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    sectorAnimateElements.forEach(element => {
        sectorObserver.observe(element);
    });
});