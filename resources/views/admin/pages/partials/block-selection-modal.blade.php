<div x-show="showBlockModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4">
    <div class="w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4 text-white dark:border-slate-700">
            <h3 class="text-base font-semibold">Add Block</h3>
            <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold cursor-pointer hover:bg-sky-500" @click="showBlockModal = false">
                Done
            </button>
        </div>
        <div class="grid gap-4 bg-slate-50 p-4 dark:bg-slate-800 sm:grid-cols-3">
            <template x-for="block in blocks" :key="block.id">
                <button type="button"
                        class="rounded border border-slate-200 bg-white text-left text-xs font-semibold text-slate-600 cursor-pointer hover:border-sky-500 hover:text-sky-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                        @click="addBlockToSection(block)">
                    <div class="rounded bg-slate-100 p-3 dark:bg-slate-800">
                        <div class="space-y-4" x-show="block.layout === 'list-sidebar'" x-cloak>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="h-20 w-full bg-slate-400"></div>
                                    <div class="space-y-1.5">
                                        <div class="h-2 w-full bg-slate-300"></div>
                                        <div class="h-2 w-full bg-slate-300"></div>
                                        <div class="h-2 w-2/3 bg-slate-300"></div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2" x-show="block.layout === 'stacked'" x-cloak>
                            <div class="flex gap-3">
                                <div class="h-10 w-16 bg-slate-400"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-2 w-3/4 bg-slate-300"></div>
                                    <div class="h-2 w-1/2 bg-slate-200"></div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="h-10 w-16 bg-slate-400"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-2 w-3/4 bg-slate-300"></div>
                                    <div class="h-2 w-1/2 bg-slate-200"></div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="h-10 w-16 bg-slate-400"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-2 w-3/4 bg-slate-300"></div>
                                    <div class="h-2 w-1/2 bg-slate-200"></div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2" x-show="block.layout === 'featured-list'" x-cloak>
                            <div class="h-16 w-full bg-slate-400"></div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="h-8 bg-slate-300"></div>
                                <div class="h-8 bg-slate-300"></div>
                                <div class="h-8 bg-slate-300"></div>
                                <div class="h-8 bg-slate-300"></div>
                            </div>
                        </div>
                        <div class="space-y-2" x-show="block.layout === 'hero-list'" x-cloak>
                            <div class="flex gap-3">
                                <div class="h-16 w-1/2 bg-slate-400"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-2 w-full bg-slate-300"></div>
                                    <div class="h-2 w-2/4 bg-slate-200"></div>
                                    <div class="h-2 w-2/4 bg-slate-200"></div>
                                    <div class="h-2 w-1/3 bg-slate-200"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4" x-show="block.layout === 'half-width'" x-cloak>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="h-12 w-full bg-slate-400"></div>
                                    <div class="space-y-1.5">
                                        <div class="h-2 w-full bg-slate-300"></div>
                                        <div class="h-2 w-2/3 bg-slate-300"></div>
                                    </div>

                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                        <div class="flex-1 space-y-1.5">
                                            <div class="h-2 w-full bg-slate-300"></div>
                                            <div class="h-2 w-3/4 bg-slate-200"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">50% WIDTH GRID</div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <p x-text="block.name" class="p-2"></p>
                </button>
            </template>
        </div>
    </div>
</div>
