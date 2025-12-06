// --- START: Immediate Theme Logic (Runs before DOMContentLoaded) ---
// This part ensures the <html> tag gets the 'dark' class immediately,
// allowing Tailwind CSS to apply initial dark mode styles, thus avoiding flicker.

const html = document.documentElement;

// Function to set theme based on localStorage or system preference
function setInitialTheme() {
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


document.addEventListener('DOMContentLoaded', function () {
    /* ---------------------------
       Mobile Menu Toggle
    ---------------------------- */
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu        = document.getElementById('mobileMenu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    /* ---------------------------
       Tabs (Latest / Popular)
    ---------------------------- */
    const latestBtn  = document.getElementById('tab-latest-btn');
    const popularBtn = document.getElementById('tab-popular-btn');
    const latestTab  = document.getElementById('tab-latest');
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
        latestBtn.addEventListener('click', showLatest);
        popularBtn.addEventListener('click', showPopular);
    }

    /* --------------------------------------
       Dark / Light Theme Toggle (Icon Control)
    --------------------------------------- */
    const toggle   = document.getElementById('themeToggle');
    const moonIcon = document.getElementById('moonIcon');
    const sunIcon  = document.getElementById('sunIcon');

    // Icon setting function - Note: html class is already set by setInitialTheme() outside DOMContentLoaded
    function setTheme(theme) {
        if (!moonIcon || !sunIcon) return;

        // **ফাইনাল ফিক্স:** Tailwind ক্লাসের পরিবর্তে সরাসরি ইনলাইন 'display' প্রোপার্টি ব্যবহার করা হয়েছে।
        if (theme === 'dark') {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');

            // সূর্য দেখান (block), চাঁদ লুকান (none)
            moonIcon.style.display = 'none';
            sunIcon.style.display = 'block';
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');

            // চাঁদ দেখান (block), সূর্য লুকান (none)
            sunIcon.style.display = 'none';
            moonIcon.style.display = 'block';
        }
    }

    // Now that the DOM (and icons) are ready, set the icon visibility based on the already set <html> class
    const isDark = html.classList.contains('dark');
    const currentTheme = isDark ? 'dark' : 'light';

    // Set the correct icon state using the robust setTheme function
    setTheme(currentTheme);


    // Add click listener for toggling
    if (toggle) {
        toggle.addEventListener('click', () => {
            const isCurrentlyDark = html.classList.contains('dark');
            const nextTheme   = isCurrentlyDark ? 'light' : 'dark';
            setTheme(nextTheme);
        });
    }

    /* ---------------------------
       Video Carousel (Horizontal scroll)
    ---------------------------- */
    const videoContainer = document.getElementById('videoCarousel');
    const prevBtn        = document.getElementById('videoCarouselPrev');
    const nextBtn        = document.getElementById('videoCarouselNext');

    if (videoContainer && prevBtn && nextBtn) {

        function scrollAmount() {
            // মোবাইল-ডেস্কটপ দুইতেই container width অনুযায়ী স্ক্রল
            return videoContainer.clientWidth * 0.9;
        }

        function updateButtons() {
            // Left side
            prevBtn.disabled = videoContainer.scrollLeft <= 0;

            // Right side
            const maxScroll = videoContainer.scrollWidth - videoContainer.clientWidth - 5;
            nextBtn.disabled = videoContainer.scrollLeft >= maxScroll;
        }

        // Initial
        updateButtons();

        prevBtn.addEventListener('click', () => {
            videoContainer.scrollBy({
                left: -scrollAmount(),
                behavior: 'smooth'
            });
            setTimeout(updateButtons, 400);
        });

        nextBtn.addEventListener('click', () => {
            videoContainer.scrollBy({
                left: scrollAmount(),
                behavior: 'smooth'
            });
            setTimeout(updateButtons, 400);
        });

        videoContainer.addEventListener('scroll', updateButtons);
        window.addEventListener('resize', updateButtons);
    }

    /* ---------------------------
       Sidebar Featured Carousel
    ---------------------------- */
    const sidebarSlider = document.getElementById('sidebarFeaturedCarousel');
    const sidebarSlides = sidebarSlider ? sidebarSlider.querySelectorAll('[data-slide]') : [];
    const sidebarPrev   = document.getElementById('sidebarFeaturedPrev');
    const sidebarNext   = document.getElementById('sidebarFeaturedNext');

    if (sidebarSlider && sidebarSlides.length > 0 && sidebarPrev && sidebarNext) {
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

        sidebarPrev.addEventListener('click', function () {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlides();
            }
        });

        sidebarNext.addEventListener('click', function () {
            if (currentIndex < sidebarSlides.length - 1) {
                currentIndex++;
                updateSlides();
            }
        });

        // Initial state
        updateSlides();
    }
});
