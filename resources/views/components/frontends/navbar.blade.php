<header class="bg-white dark:bg-slate-900/95 shadow-sm sticky top-0 z-50
               border-b border-slate-200/70 dark:border-slate-700/70
               backdrop-blur transition-colors duration-300">
    <div class="container flex items-center justify-between px-4 py-3">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">NP</div>
            <div>
                <div class="text-xl font-bold text-primary-dark dark:text-primary-light">বাংলা নিউজ পোর্টাল</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">বিশ্বস্ত খবর আপনার হাতের মুঠোয়</div>
            </div>
        </a>
        <div class="flex items-center gap-3">
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="index.html" class="text-primary-dark dark:text-primary-light relative transition-colors duration-150">হোম</a>
                <a href="category.html" class="text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">জাতীয়</a>
                <a href="#" class="text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">আন্তর্জাতিক</a>
                <a href="#" class="text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">খেলা</a>
                <a href="#" class="text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">বিনোদন</a>
                <a href="#" class="text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">তথ্যপ্রযুক্তি</a>
                <a href="#" class="text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">বাণিজ্য</a>
            </nav>
            <button id="mobileMenuButton" class="md:hidden inline-flex items-center justify-center w-10 h-10 border rounded-lg border-slate-300 dark:border-slate-600">
                <span class="sr-only">Toggle navigation</span>
                <div class="space-y-1.5">
                    <span class="block w-5 h-0.5 bg-slate-800 dark:bg-slate-100"></span>
                    <span class="block w-5 h-0.5 bg-slate-800 dark:bg-slate-100"></span>
                    <span class="block w-5 h-0.5 bg-slate-800 dark:bg-slate-100"></span>
                </div>
            </button>

            <!-- লগইন/ড্যাশবোর্ড বাটন (ডেস্কটপ) -->
            @auth
                <a href="/dashboard" class="px-3 py-2 text-sm font-medium rounded-lg bg-primary hover:bg-primary/90 text-white transition-colors duration-150 flex items-center gap-2">
                    <i class="fa-solid fa-gauge-high text-xs"></i> ড্যাশবোর্ড
                </a>
            @else
                <a href="/login" class="px-3 py-2 text-sm font-medium rounded-lg bg-accent hover:bg-accent/90 text-white transition-colors duration-150 flex items-center gap-2">
                    <i class="fa-solid fa-sign-in-alt text-xs"></i> লগইন
                </a>
            @endauth
            <!-- /লগইন/ড্যাশবোর্ড বাটন -->

            <button id="themeToggle" class="inline-flex cursor-pointer items-center justify-center w-9 h-9 rounded-full border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i id="moonIcon" class="fa-solid fa-moon text-sm"></i>
                <i id="sunIcon" class="fa-solid fa-sun text-sm hidden"></i>
            </button>
        </div>
    </div>
    <div class="bg-white dark:bg-secondary dark:text-white text-sm border-t dark:border-t-darkbg-soft">
        <div class="container flex items-center gap-4 px-4 py-2">
            <span class="bg-accent text-white px-2 py-1 text-xs font-semibold rounded">ব্রেকিং নিউজ</span>
            <div class="overflow-hidden flex-1">
                <div class="whitespace-nowrap animate-marquee">
                    <a href="#" class="mr-8 hover:underline">প্রধানমন্ত্রীর ভারত সফর আগামী সপ্তাহে, আলোচনায় বাণিজ্য ও নিরাপত্তা…</a>
                    <a href="#" class="mr-8 hover:underline">শেয়ারবাজারে বড় উত্থান, বিনিয়োগকারীদের মুখে হাসি…</a>
                    <a href="#" class="hover:underline">টি-টোয়েন্টি সিরিজে বাংলাদেশের দাপুটে জয়…</a>
                </div>
            </div>
        </div>
    </div>
    <nav id="mobileMenu"
         class="md:hidden bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-700 px-4 pt-2 pb-4 space-y-1 hidden">
        <div class="container px-0">
            <!-- লগইন/ড্যাশবোর্ড বাটন (মোবাইল) -->
            @auth
                <a href="/dashboard" class="block px-2 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 transition-colors duration-150">
                    <i class="fa-solid fa-gauge-high text-xs mr-2"></i>ড্যাশবোর্ড
                </a>
            @else
                <a href="/login" class="block px-2 py-2 rounded-md text-sm font-medium text-white bg-accent hover:bg-accent/90 transition-colors duration-150">
                    <i class="fa-solid fa-sign-in-alt text-xs mr-2"></i>লগইন
                </a>
            @endauth
            <!-- /লগইন/ড্যাশবোর্ড বাটন -->

            <a href="index.html" class="block px-2 py-2 rounded-md text-sm font-medium text-primary-dark dark:text-primary-light bg-primary-light/70 dark:bg-slate-800 mt-2">হোম</a>
            <a href="category.html" class="block px-2 py-2 rounded-md text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">জাতীয়</a>
            <a href="#" class="block px-2 py-2 rounded-md text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">আন্তর্জাতিক</a>
            <a href="#" class="block px-2 py-2 rounded-md text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">খেলা</a>
            <a href="#" class="block px-2 py-2 rounded-md text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">বিনোদন</a>
            <a href="#" class="block px-2 py-2 rounded-md text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">তথ্যপ্রযুক্তি</a>
            <a href="#" class="block px-2 py-2 rounded-md text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">বাণিজ্য</a>
        </div>
    </nav>
</header>
