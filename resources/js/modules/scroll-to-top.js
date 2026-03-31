export function setupScrollToTop() {
    const button = document.getElementById('scrollToTopBtn');

    if (!button) {
        return;
    }
    if (button.dataset.initialized === 'true') {
        return;
    }
    button.dataset.initialized = 'true';

    const toggleVisibility = () => {
        if (window.scrollY > 220) {
            button.classList.remove('hidden');
            return;
        }

        button.classList.add('hidden');
    };

    const speed = Number.parseInt(button.dataset.scrollSpeed || '500', 10);

    button.addEventListener('click', () => {
        const duration = Math.max(100, speed);
        const startY = window.scrollY;
        const start = performance.now();

        const animate = (now) => {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - (1 - progress) ** 3;
            window.scrollTo(0, Math.max(0, Math.floor(startY * (1 - eased))));

            if (progress < 1) {
                window.requestAnimationFrame(animate);
            }
        };

        window.requestAnimationFrame(animate);
    });

    window.addEventListener('scroll', toggleVisibility, { passive: true });
    document.addEventListener('livewire:navigated', toggleVisibility);
    toggleVisibility();
}
