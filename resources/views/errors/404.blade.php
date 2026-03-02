<x-layouts.frontend.app title="পেজ পাওয়া যায়নি">
    <section class="container px-4 py-16 md:py-24">
        <div class="max-w-3xl mx-auto text-center bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/70 dark:border-slate-700 p-8 md:p-12">
            <p class="text-sm font-semibold tracking-widest text-primary-dark dark:text-primary-light uppercase">404 Error</p>
            <h1 class="mt-3 text-3xl md:text-5xl font-bold text-slate-900 dark:text-slate-100">দুঃখিত! পেজটি খুঁজে পাওয়া যায়নি</h1>
            <p class="mt-4 text-slate-600 dark:text-slate-300 text-sm md:text-base leading-relaxed">
                আপনি যে লিংকটি খুঁজছেন সেটি হয়তো সরানো হয়েছে, নাম পরিবর্তন হয়েছে, অথবা সাময়িকভাবে অনুপলব্ধ।
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-lg bg-primary-dark px-6 py-3 text-sm font-semibold text-white hover:opacity-90 transition" wire:navigate>
                    <i class="fa-solid fa-house mr-2"></i>
                    হোমপেজে ফিরে যান
                </a>
                <a href="{{ route('google.search') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 dark:border-slate-600 px-6 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition" wire:navigate>
                    <i class="fa-solid fa-magnifying-glass mr-2"></i>
                    সার্চ করুন
                </a>
            </div>
        </div>
    </section>
</x-layouts.frontend.app>
