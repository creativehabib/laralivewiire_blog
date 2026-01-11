<div x-show="showSectionModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4">
    <div class="w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4 text-white dark:border-slate-700">
            <h3 class="text-base font-semibold">Edit Section</h3>
            <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold cursor-pointer hover:bg-sky-500" @click="showSectionModal = false">
                Done
            </button>
        </div>
        <div class="flex bg-sky-600 text-xs font-semibold text-white">
            <button type="button" class="relative px-5 py-3 cursor-pointer" :class="sectionTab === 'general' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'general'">
                General
                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="sectionTab === 'general'"></span>
            </button>
            <button type="button" class="relative px-5 py-3 cursor-pointer" :class="sectionTab === 'background' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'background'">
                Background
                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="sectionTab === 'background'"></span>
            </button>
            <button type="button" class="relative px-5 py-3 cursor-pointer" :class="sectionTab === 'styling' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'styling'">
                Styling
                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="sectionTab === 'styling'"></span>
            </button>
        </div>
        <div class="max-h-[70vh] space-y-6 overflow-y-auto bg-slate-50 p-6 dark:bg-slate-800">
            <div class="space-y-4 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900" x-show="sectionTab === 'general'" x-cloak>
                <div class="border-b border-slate-200 pb-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">
                    Section Title
                </div>
                <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                    <span>Section Title</span>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" class="peer sr-only">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <span class="h-5 w-5 translate-x-1 rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="space-y-4 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900" x-show="sectionTab === 'general'" x-cloak>
                <div class="border-b border-slate-200 pb-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">
                    Section Layout
                </div>
                <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                    <div>
                        <p class="font-medium">Stretch Section</p>
                        <p class="text-xs text-slate-500">Stretch the section to the full width of the page, supported if the site layout is Full-Width.</p>
                    </div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" class="peer sr-only">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <span class="h-5 w-5 translate-x-1 rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="space-y-4 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900" x-show="sectionTab === 'general'" x-cloak>
                <div class="border-b border-slate-200 pb-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">
                    Sidebar Settings
                </div>
                <p class="text-xs text-slate-500">Sidebar Position</p>
                <div class="grid gap-4 sm:grid-cols-3">
                    <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold cursor-pointer text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            :class="selectedSidebar === 'none' ? 'border-sky-600 ring-2 ring-sky-200 dark:ring-sky-600/40' : ''"
                            @click="updateSidebarSelection('none')">
                        <div class="mb-2 h-16 rounded bg-slate-200 dark:bg-slate-700"></div>
                        Without Sidebar
                    </button>
                    <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold cursor-pointer text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            :class="selectedSidebar === 'right' ? 'border-sky-600 ring-2 ring-sky-200 dark:ring-sky-600/40' : ''"
                            @click="updateSidebarSelection('right')">
                        <div class="mb-2 h-16 rounded bg-slate-200 dark:bg-slate-700">
                            <div class="h-full w-4/5 rounded bg-slate-300 dark:bg-slate-600"></div>
                        </div>
                        Sidebar Right
                    </button>
                    <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold cursor-pointer text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            :class="selectedSidebar === 'left' ? 'border-sky-600 ring-2 ring-sky-200 dark:ring-sky-600/40' : ''"
                            @click="updateSidebarSelection('left')">
                        <div class="mb-2 h-16 rounded bg-slate-200 dark:bg-slate-700">
                            <div class="h-full w-1/5 rounded bg-slate-400 dark:bg-slate-600"></div>
                        </div>
                        Sidebar Left
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
