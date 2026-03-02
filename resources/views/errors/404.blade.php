<x-layouts.frontend.app title="পেজ পাওয়া যায়নি">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-slate-100 via-slate-50 to-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-950"></div>
        <div class="absolute -top-28 -left-20 h-72 w-72 rounded-full bg-primary-dark/10 blur-3xl -z-10"></div>
        <div class="absolute -bottom-24 -right-20 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl -z-10"></div>

        <div class="container px-4 py-16 md:py-24">
            <div class="max-w-4xl mx-auto rounded-3xl border border-white/50 dark:border-slate-700/70 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-xl p-7 md:p-12 text-center">
                <p class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs md:text-sm font-semibold bg-primary-dark/10 text-primary-dark dark:bg-primary-light/10 dark:text-primary-light">
                    <span class="w-2 h-2 rounded-full bg-primary-dark dark:bg-primary-light"></span>
                    404 • Page Not Found
                </p>

                <h1 class="mt-5 text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white">ওহ না! পেজটি পাওয়া যায়নি</h1>

                <p class="mt-4 max-w-2xl mx-auto text-sm md:text-base leading-relaxed text-slate-600 dark:text-slate-300">
                    আপনি যে পেইজটি খুঁজছেন সেটি হয়তো মুছে ফেলা হয়েছে, ঠিকানা পরিবর্তন হয়েছে,
                    অথবা লিংকে কোনো টাইপো থাকতে পারে।
                </p>

                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('home') }}" class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-primary-dark px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-dark/20 hover:scale-[1.02] transition" wire:navigate>
                        <i class="fa-solid fa-house mr-2"></i>
                        হোমপেজে ফিরে যান
                    </a>

                    <a href="{{ route('google.search') }}" class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl border border-slate-300 dark:border-slate-600 px-6 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition" wire:navigate>
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>
                        সার্চ পেইজে যান
                    </a>
                </div>

                <div class="mt-10 grid sm:grid-cols-3 gap-3 text-left">
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-800/50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Quick Tip</p>
                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-200">URL বানান ঠিক আছে কিনা আবার চেক করুন।</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-800/50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Explore</p>
                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-200">হোমপেজ থেকে সর্বশেষ নিউজ ব্রাউজ করুন।</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-800/50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Find Faster</p>
                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-200">সার্চ ব্যবহার করে কাঙ্ক্ষিত পোস্ট খুঁজে নিন।</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.frontend.app>
