// --- START: Immediate Theme Logic (Runs before DOMContentLoaded) ---
// এই অংশটা স্ক্রিপ্ট লোড হওয়ার সাথে সাথে রান হবে,
// যাতে <html> ট্যাগে 'dark' ক্লাস আগে থেকেই যুক্ত হয়
// এবং Tailwind dark mode লোড হবার সময়ে ফ্লিকার (হঠাৎ লাইট থেকে ডার্ক) না দেখা যায়।

// <html> এলিমেন্ট পাওয়ার জন্য হেল্পার ফাংশন
function getHtmlElement() {
    return document.documentElement;
}

// localStorage বা system preference দেখে initial theme সেট করার ফাংশন
function setInitialTheme() {
    const html = getHtmlElement();
    const storedTheme = localStorage.getItem('theme'); // আগে কিছু সেভ করা আছে কি না
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

    let initialTheme = 'light';

    // যদি localStorage-এ theme সেভ থাকে, সেটাকেই প্রাধান্য দেব
    if (storedTheme === 'dark' || storedTheme === 'light') {
        initialTheme = storedTheme;
    } else if (prefersDark) {
        // না থাকলে OS-এর ডার্ক মোড পছন্দ দেখবো
        initialTheme = 'dark';
    }

    // <html> ট্যাগে dark ক্লাস অ্যাড বা রিমুভ
    if (initialTheme === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
}

// স্ক্রিপ্ট লোড হওয়ার সাথে সাথেই থিম সেট করে ফেলি
setInitialTheme();

// --- END: Immediate Theme Logic ---



// ----------------------
// ইভেন্ট লিসেনার safely add করার হেল্পার
// ----------------------
function addUniqueListener(element, eventName, handlerKey, handler) {
    if (!element) return;

    // আগের কোনো হ্যান্ডলার থাকলে আগে সেটা সরিয়ে ফেলি
    if (element[handlerKey]) {
        element.removeEventListener(eventName, element[handlerKey]);
    }

    // নতুন হ্যান্ডলার সেট করে দিই
    element[handlerKey] = handler;
    element.addEventListener(eventName, handler);
}



// ----------------------
// মোবাইল মেনু টগল
// ----------------------
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    if (!mobileMenuButton || !mobileMenu) return;

    // বাটনে ক্লিক করলে hidden ক্লাস টগল হবে
    addUniqueListener(mobileMenuButton, 'click', '__mobileMenuHandler', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// ----------------------
// ডেস্কটপ সার্চ টগল
// ----------------------
function initDesktopSearchToggle() {
    const toggleButton = document.getElementById('desktopSearchToggle');
    const searchWrapper = document.getElementById('desktopSearchWrapper');

    if (!toggleButton || !searchWrapper) return;

    function closeSearch() {
        if (!searchWrapper.classList.contains('hidden')) {
            searchWrapper.classList.add('hidden');
        }
    }

    addUniqueListener(toggleButton, 'click', '__desktopSearchHandler', (event) => {
        event.stopPropagation();
        searchWrapper.classList.toggle('hidden');

        if (!searchWrapper.classList.contains('hidden')) {
            const input = searchWrapper.querySelector('input[type="search"]');
            if (input) {
                input.focus();
            }
        }
    });

    addUniqueListener(document, 'click', '__desktopSearchOutsideHandler', (event) => {
        if (searchWrapper.classList.contains('hidden')) return;
        if (searchWrapper.contains(event.target) || toggleButton.contains(event.target)) {
            return;
        }
        closeSearch();
    });

    const input = searchWrapper.querySelector('input[type="search"]');
    if (input) {
        addUniqueListener(input, 'keydown', '__desktopSearchEscapeHandler', (event) => {
            if (event.key === 'Escape') {
                closeSearch();
                toggleButton.focus();
            }
        });
    }
}



// ----------------------
// ট্যাব (Latest / Popular) হ্যান্ডলিং
// ----------------------
function initTabs() {
    const latestBtn = document.getElementById('tab-latest-btn');
    const popularBtn = document.getElementById('tab-popular-btn');
    const latestTab = document.getElementById('tab-latest');
    const popularTab = document.getElementById('tab-popular');

    // Latest ট্যাব দেখানোর ফাংশন
    function showLatest() {
        if (!latestTab || !popularTab || !latestBtn || !popularBtn) return;

        latestTab.classList.remove('hidden');
        popularTab.classList.add('hidden');

        latestBtn.classList.add('border-primary-dark', 'text-primary-dark');
        latestBtn.classList.remove('text-slate-600', 'border-transparent');

        popularBtn.classList.remove('border-primary-dark', 'text-primary-dark');
        popularBtn.classList.add('text-slate-600', 'border-transparent');
    }

    // Popular ট্যাব দেখানোর ফাংশন
    function showPopular() {
        if (!latestTab || !popularTab || !latestBtn || !popularBtn) return;

        popularTab.classList.remove('hidden');
        latestTab.classList.add('hidden');

        popularBtn.classList.add('border-primary-dark', 'text-primary-dark');
        popularBtn.classList.remove('text-slate-600', 'border-transparent');

        latestBtn.classList.remove('border-primary-dark', 'text-primary-dark');
        latestBtn.classList.add('text-slate-600', 'border-transparent');
    }

    // বাটন থাকলে ইভেন্ট লিসেনার অ্যাটাচ করি
    if (latestBtn && popularBtn) {
        addUniqueListener(latestBtn, 'click', '__latestTabHandler', showLatest);
        addUniqueListener(popularBtn, 'click', '__popularTabHandler', showPopular);
    }

    // চাইলে এখানে default হিসাবে Latest দেখাতে পারো:
    // showLatest();
}



// ----------------------
// থিম টগল (Dark / Light + আইকন স্যুইচ)
// ----------------------
function initThemeToggle() {
    const toggle = document.getElementById('themeToggle');
    const html = getHtmlElement();

    // থিম সেট + আইকন টগল
    function setTheme(theme) {
        const html = getHtmlElement();
        const moonIcon = document.getElementById('moonIcon');
        const sunIcon = document.getElementById('sunIcon');

        if (!moonIcon || !sunIcon) return;

        if (theme === 'dark') {
            // থিম
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');

            // আইকন টগল (display দিয়ে)
            moonIcon.style.display = 'none';
            sunIcon.style.display = 'inline-block';
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');

            sunIcon.style.display = 'none';
            moonIcon.style.display = 'inline-block';
        }
    }

    // পেজ লোডের সময় current থিম থেকে আইকন সেট করা
    const isDark = html && html.classList.contains('dark');
    const currentTheme = isDark ? 'dark' : 'light';
    setTheme(currentTheme);

    // বাটনে ক্লিক করলে থিম টগল
    if (toggle && html) {
        addUniqueListener(toggle, 'click', '__themeToggleHandler', () => {
            const isCurrentlyDark = html.classList.contains('dark');
            const nextTheme = isCurrentlyDark ? 'light' : 'dark';
            setTheme(nextTheme);
        });
    }
}



// ----------------------
// ভিডিও ক্যারোসেল (হরাইজন্টাল স্ক্রল)
// ----------------------
function initVideoCarousel() {
    const videoContainer = document.getElementById('videoCarousel');
    const prevBtn = document.getElementById('videoCarouselPrev');
    const nextBtn = document.getElementById('videoCarouselNext');

    if (!(videoContainer && prevBtn && nextBtn)) return;

    // একবারে কতটা স্ক্রল হবে
    function scrollAmount() {
        return videoContainer.clientWidth * 0.9;
    }

    // বাটনের enable/disable হালনাগাদ করা
    function updateButtons() {
        // বামদিকে আর স্ক্রল করার মতো জায়গা না থাকলে prev disabled
        prevBtn.disabled = videoContainer.scrollLeft <= 0;

        const maxScroll = videoContainer.scrollWidth - videoContainer.clientWidth - 5;
        // ডানদিকে আর স্ক্রল করার মতো জায়গা না থাকলে next disabled
        nextBtn.disabled = videoContainer.scrollLeft >= maxScroll;
    }

    updateButtons();

    // পূর্বের দিকে স্ক্রল
    addUniqueListener(prevBtn, 'click', '__videoPrevHandler', () => {
        videoContainer.scrollBy({
            left: -scrollAmount(),
            behavior: 'smooth'
        });
        // স্মুথ স্ক্রলের একটু পর বাটনের স্টেট আপডেট
        setTimeout(updateButtons, 400);
    });

    // পরের দিকে স্ক্রল
    addUniqueListener(nextBtn, 'click', '__videoNextHandler', () => {
        videoContainer.scrollBy({
            left: scrollAmount(),
            behavior: 'smooth'
        });
        setTimeout(updateButtons, 400);
    });

    // স্ক্রল বা রিসাইজ হলে বাটন স্টেট আপডেট
    addUniqueListener(videoContainer, 'scroll', '__videoScrollHandler', updateButtons);
    addUniqueListener(window, 'resize', '__videoResizeHandler', updateButtons);
}



// ----------------------
// Sidebar ক্যারোসেল (একটা করে স্লাইড দেখাবে)
// ----------------------
function initSidebarCarousel() {
    const sidebarSlider = document.getElementById('sidebarFeaturedCarousel');
    const sidebarSlides = sidebarSlider ? sidebarSlider.querySelectorAll('[data-slide]') : [];
    const sidebarPrev = document.getElementById('sidebarFeaturedPrev');
    const sidebarNext = document.getElementById('sidebarFeaturedNext');

    if (!(sidebarSlider && sidebarSlides.length > 0 && sidebarPrev && sidebarNext)) return;

    let currentIndex = 0; // বর্তমান স্লাইড ইনডেক্স

    // কোন স্লাইড শো হবে, তা আপডেট করা
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

        // প্রথম স্লাইডে থাকলে prev disabled
        sidebarPrev.disabled = currentIndex === 0;
        // শেষ স্লাইডে থাকলে next disabled
        sidebarNext.disabled = currentIndex === sidebarSlides.length - 1;
    }

    updateSlides();

    // পূর্বের স্লাইড
    addUniqueListener(sidebarPrev, 'click', '__sidebarPrevHandler', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlides();
        }
    });

    // পরের স্লাইড
    addUniqueListener(sidebarNext, 'click', '__sidebarNextHandler', () => {
        if (currentIndex < sidebarSlides.length - 1) {
            currentIndex++;
            updateSlides();
        }
    });
}



// ----------------------
// পেজের সব ইন্টারঅ্যাকশন initialize করার main ফাংশন
// ----------------------
function initPageInteractions() {
    // setInitialTheme();
    // উপরে স্ক্রিপ্ট লোড হওয়ার সাথে সাথেই একবার থিম সেট করেছি,
    // তাই এখানে আবার কল না করলেও চলে (ডাবল কাজ এড়াতে চাইলে এই লাইনটা কমেন্ট রাখা ভালো)

    initMobileMenu();
    initDesktopSearchToggle();
    initTabs();
    initThemeToggle();
    initVideoCarousel();
    initSidebarCarousel();
}



// ----------------------
// DOM ready / Livewire ইভেন্টের পর init চালানো
// ----------------------
function runInitPageInteractions() {
    // DOM একটু settle হোক, তারপর ফাংশনগুলো চালাই
    requestAnimationFrame(initPageInteractions);
}

// সাধারণ পেজ লোড
document.addEventListener('DOMContentLoaded', runInitPageInteractions);

// Livewire প্রথমবার লোড হলে
document.addEventListener('livewire:load', runInitPageInteractions);

// Livewire দিয়ে নেভিগেশনের পর
document.addEventListener('livewire:navigated', () => {
    // নতুন পেজে গেলে যেন মোবাইল মেনু সবসময় বন্ধ অবস্থায় থাকে
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.add('hidden');
    }

    runInitPageInteractions();
});
