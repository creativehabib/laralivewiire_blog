// --- START: Immediate Theme Logic (Runs before DOMContentLoaded) ---
// This part ensures the <html> tag gets the 'dark' class immediately,
// allowing Tailwind CSS to apply initial dark mode styles, thus avoiding flicker.

function getHtmlElement() {
    return document.documentElement;
}

// Function to set theme based on localStorage or system preference
function setInitialTheme() {
    const html = getHtmlElement();
    const storedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

    let initialTheme = 'light';

    if (storedTheme === 'dark' || storedTheme === 'light') {
        initialTheme = storedTheme;
    } else if (prefersDark) {
        initialTheme = 'dark';
    }

    if (initialTheme === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
}

// Execute immediately to set the 'dark' class on <html> tag
setInitialTheme();

// --- END: Immediate Theme Logic ---

function addUniqueListener(element, eventName, handlerKey, handler) {
    if (!element) return;

    if (element[handlerKey]) {
        element.removeEventListener(eventName, element[handlerKey]);
    }

    element[handlerKey] = handler;
    element.addEventListener(eventName, handler);
}

function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    if (!mobileMenuButton || !mobileMenu) return;

    addUniqueListener(mobileMenuButton, 'click', '__mobileMenuHandler', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

function initTabs() {
    const latestBtn = document.getElementById('tab-latest-btn');
    const popularBtn = document.getElementById('tab-popular-btn');
    const latestTab = document.getElementById('tab-latest');
    const popularTab = document.getElementById('tab-popular');

    function showLatest() {
        if (!latestTab || !popularTab || !latestBtn || !popularBtn) return;

        latestTab.classList.remove('hidden');
        popularTab.classList.add('hidden');

        latestBtn.classList.add('border-primary-dark', 'text-primary-dark');
        latestBtn.classList.remove('text-slate-600', 'border-transparent');

        popularBtn.classList.remove('border-primary-dark', 'text-primary-dark');
        popularBtn.classList.add('text-slate-600', 'border-transparent');
    }

    function showPopular() {
        if (!latestTab || !popularTab || !latestBtn || !popularBtn) return;

        popularTab.classList.remove('hidden');
        latestTab.classList.add('hidden');

        popularBtn.classList.add('border-primary-dark', 'text-primary-dark');
        popularBtn.classList.remove('text-slate-600', 'border-transparent');

        latestBtn.classList.remove('border-primary-dark', 'text-primary-dark');
        latestBtn.classList.add('text-slate-600', 'border-transparent');
    }

    if (latestBtn && popularBtn) {
        addUniqueListener(latestBtn, 'click', '__latestTabHandler', showLatest);
        addUniqueListener(popularBtn, 'click', '__popularTabHandler', showPopular);
    }
}

function initThemeToggle() {
    const toggle = document.getElementById('themeToggle');
    const moonIcon = document.getElementById('moonIcon');
    const sunIcon = document.getElementById('sunIcon');

    function setTheme(theme) {
        const html = getHtmlElement();
        if (!moonIcon || !sunIcon) return;

        if (theme === 'dark') {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');

            moonIcon.style.display = 'none';
            sunIcon.style.display = 'block';
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');

            sunIcon.style.display = 'none';
            moonIcon.style.display = 'block';
        }
    }

    const isDark = html.classList.contains('dark');
    const currentTheme = isDark ? 'dark' : 'light';
    setTheme(currentTheme);

    if (toggle) {
        addUniqueListener(toggle, 'click', '__themeToggleHandler', () => {
            const isCurrentlyDark = html.classList.contains('dark');
            const nextTheme = isCurrentlyDark ? 'light' : 'dark';
            setTheme(nextTheme);
        });
    }
}

function initVideoCarousel() {
    const videoContainer = document.getElementById('videoCarousel');
    const prevBtn = document.getElementById('videoCarouselPrev');
    const nextBtn = document.getElementById('videoCarouselNext');

    if (!(videoContainer && prevBtn && nextBtn)) return;

    function scrollAmount() {
        return videoContainer.clientWidth * 0.9;
    }

    function updateButtons() {
        prevBtn.disabled = videoContainer.scrollLeft <= 0;

        const maxScroll = videoContainer.scrollWidth - videoContainer.clientWidth - 5;
        nextBtn.disabled = videoContainer.scrollLeft >= maxScroll;
    }

    updateButtons();

    addUniqueListener(prevBtn, 'click', '__videoPrevHandler', () => {
        videoContainer.scrollBy({
            left: -scrollAmount(),
            behavior: 'smooth'
        });
        setTimeout(updateButtons, 400);
    });

    addUniqueListener(nextBtn, 'click', '__videoNextHandler', () => {
        videoContainer.scrollBy({
            left: scrollAmount(),
            behavior: 'smooth'
        });
        setTimeout(updateButtons, 400);
    });

    addUniqueListener(videoContainer, 'scroll', '__videoScrollHandler', updateButtons);
    addUniqueListener(window, 'resize', '__videoResizeHandler', updateButtons);
}

function initSidebarCarousel() {
    const sidebarSlider = document.getElementById('sidebarFeaturedCarousel');
    const sidebarSlides = sidebarSlider ? sidebarSlider.querySelectorAll('[data-slide]') : [];
    const sidebarPrev = document.getElementById('sidebarFeaturedPrev');
    const sidebarNext = document.getElementById('sidebarFeaturedNext');

    if (!(sidebarSlider && sidebarSlides.length > 0 && sidebarPrev && sidebarNext)) return;

    let currentIndex = 0;

    function updateSlides() {
        sidebarSlides.forEach((slide, index) => {
            if (index === currentIndex) {
                slide.classList.remove('hidden');
                slide.classList.add('block');
            } else {
                slide.classList.add('hidden');
                slide.classList.remove('block');
            }
        });

        sidebarPrev.disabled = currentIndex === 0;
        sidebarNext.disabled = currentIndex === sidebarSlides.length - 1;
    }

    updateSlides();

    addUniqueListener(sidebarPrev, 'click', '__sidebarPrevHandler', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlides();
        }
    });

    addUniqueListener(sidebarNext, 'click', '__sidebarNextHandler', () => {
        if (currentIndex < sidebarSlides.length - 1) {
            currentIndex++;
            updateSlides();
        }
    });
}

function initPageInteractions() {
    setInitialTheme();
    initMobileMenu();
    initTabs();
    initThemeToggle();
    initVideoCarousel();
    initSidebarCarousel();
}

document.addEventListener('DOMContentLoaded', initPageInteractions);
document.addEventListener('livewire:navigated', initPageInteractions);
