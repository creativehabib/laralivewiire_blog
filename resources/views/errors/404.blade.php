<x-layouts.frontend.app title="পেজ পাওয়া যায়নি">
    <section class="relative overflow-hidden bg-[#f3f3f4] text-slate-900 dark:bg-slate-950 dark:text-white">
        <div class="absolute -top-10 left-[55%] h-44 w-44 rounded-full bg-sky-200/60 dark:bg-sky-500/20"></div>
        <div class="absolute top-24 right-24 h-64 w-64 rounded-full bg-sky-100/70 dark:bg-sky-500/10"></div>
        <div class="absolute bottom-20 right-1/4 h-2 w-2 bg-cyan-500"></div>
        <div class="absolute top-1/3 right-[18%] h-2 w-2 bg-pink-500"></div>
        <div class="absolute top-20 right-[12%] text-3xl leading-none text-sky-500">+</div>
        <div class="absolute top-40 right-[8%] text-4xl leading-none text-blue-500">+</div>

        <div class="container px-4 py-14 md:py-24">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <p class="text-sm font-medium uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Error code: 404</p>
                    <h1 class="mt-4 text-5xl font-black tracking-tight md:text-7xl">OOOPS!!</h1>
                    <p class="mt-4 max-w-md text-3xl font-medium leading-tight md:text-5xl">This is not the page you are looking for</p>

                    <div class="mt-12">
                        <p class="text-xl text-slate-600 dark:text-slate-300">Here are some helpful links instead:</p>
                        <div class="mt-4 flex flex-wrap gap-x-7 gap-y-3 text-lg font-medium">
                            <a href="{{ route('home') }}" class="underline underline-offset-8 hover:text-primary-dark transition" wire:navigate>Home</a>
                            <a href="{{ route('google.search') }}" class="underline underline-offset-8 hover:text-primary-dark transition" wire:navigate>Search</a>
                            <a href="{{ route('sitemap.index') }}" class="underline underline-offset-8 hover:text-primary-dark transition" wire:navigate>Sitemap</a>
                        </div>
                    </div>
                </div>

                <div class="relative flex min-h-[360px] items-center justify-center lg:min-h-[500px]">
                    <div class="absolute -top-4 left-4 h-[2px] w-36 bg-pink-400/70"></div>
                    <div class="absolute left-10 top-5 h-24 w-40 bg-[radial-gradient(circle,_#ff5f7b_1.3px,_transparent_1.6px)] [background-size:11px_11px] opacity-45"></div>
                    <h2 class="text-[10rem] font-black leading-none tracking-[-0.08em] sm:text-[13rem] lg:text-[16rem]">
                        <span class="text-yellow-400">4</span><span class="text-pink-500">0</span><span class="text-yellow-400">4</span>
                    </h2>

                    <div class="absolute right-8 top-8 text-[7rem] font-black leading-none tracking-[-0.06em] text-violet-500/90">/</div>

                    <div class="absolute inset-x-0 bottom-0 mx-auto flex h-44 w-44 items-center justify-center rounded-full border-4 border-cyan-500/40 bg-white/80 text-7xl shadow-xl dark:bg-slate-900/80">
                        🚀
                    </div>

                    <svg class="absolute -bottom-3 left-6 w-[85%]" viewBox="0 0 500 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M5 72C54 18 118 121 168 72C210 30 237 36 274 68C321 108 349 104 387 66C433 20 470 63 495 40" stroke="#0ea5e9" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>
</x-layouts.frontend.app>
