@php
    $title = '৯ম পে স্কেল ক্যালকুলেটর ২০২৬ - নতুন জাতীয় বেতন স্কেল ও ভাতাসমূহ হিসাব';
    $description = 'জাতীয় বেতনস্কেল ২০২৬ (৯ম পে স্কেল) ক্যালকুলেটর। ১ম থেকে ২০তম গ্রেডের ২০১৫ সালের মূল বেতন দিয়ে ২০২৬ সালের নতুন মূল বেতন, ৪টি ধাপের ইনক্রিমেন্ট, বাড়িভাড়া, চিকিৎসা, শিক্ষা ও অন্যান্য ভাতাসহ মোট বেতন তাৎক্ষণিক হিসাব করুন।';
    $shareUrl = urlencode(url()->current());
    $shareText = urlencode('৯ম পে স্কেল ২০২৬ (খসড়া প্রস্তাবনা)-এ আপনার নতুন বেতন ও ভাতা কত হবে, এখনই হিসাব করুন:');
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6 space-y-5 sm:space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
        <a href="/" class="hover:text-emerald-600 transition-colors">হোম</a>
        <span>›</span>
        <span class="text-slate-800 dark:text-slate-200 font-bold">জাতীয় বেতনস্কেল ২০২৬ ক্যালকুলেটর</span>
    </nav>

    <!-- Masthead -->
    <div class="flex items-start gap-3">
        <span class="hidden sm:block w-1 self-stretch rounded-full bg-emerald-500 shrink-0 mt-1"></span>
        <div class="flex-1 min-w-0 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
            <div class="space-y-1.5 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        জাতীয় বেতনস্কেল ২০২৬ ক্যালকুলেটর
                    </h1>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold shrink-0">১ম থেকে ২০তম গ্রেড</span>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-300 text-[11px] font-bold shrink-0">৯ম পে স্কেল (খসড়া)</span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed">
                    প্রস্তাবিত ৯ম জাতীয় বেতন স্কেলের খসড়া কাঠামো অনুযায়ী আপনার বর্তমান মূল বেতন দিয়ে নতুন মূল বেতন, ৪ ধাপের বাস্তবায়ন ও ভাতাসমূহ হিসাব করুন।
                </p>
            </div>

            <!-- Quick actions -->
            <div class="flex items-center gap-1.5 overflow-x-auto shrink-0 [&::-webkit-scrollbar]:hidden" style="scrollbar-width:none;">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="shrink-0 px-3 py-2 rounded-xl bg-[#1877F2] hover:bg-[#166fe5] text-white font-bold text-xs inline-flex items-center justify-center gap-1.5 transition-colors" title="ফেসবুকে শেয়ার করুন">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
                    <span class="hidden sm:inline">ফেসবুক</span>
                </a>
                <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="shrink-0 px-3 py-2 rounded-xl bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold text-xs inline-flex items-center justify-center gap-1.5 transition-colors" title="WhatsApp-এ শেয়ার করুন">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"></path></svg>
                    <span class="hidden sm:inline">WhatsApp</span>
                </a>
                <button type="button" onclick="shareCopyLink(this)" class="shrink-0 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-500 text-slate-700 dark:text-slate-200 font-bold text-xs inline-flex items-center justify-center gap-1.5 transition-colors cursor-pointer" title="ক্যালকুলেটর লিংক কপি করুন">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span class="share-copy-text hidden sm:inline">লিংক কপি</span>
                </button>
                <button type="button" onclick="resetCalculator()" class="shrink-0 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs inline-flex items-center justify-center gap-1.5 transition-colors cursor-pointer" title="রিসেট করুন">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span class="hidden sm:inline">রিসেট</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Calculator Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

        <!-- Calculator Form -->
        <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 sm:p-7 md:p-8 space-y-6">

            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold shrink-0">💰</span>
                    <div>
                        <h2 class="text-base sm:text-xl font-extrabold text-slate-900 dark:text-white">
                            বেতন ও ভাতার তথ্য দিন
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            মাত্র ৩টি সহজ ধাপে আপনার নতুন পে স্কেল হিসাব করুন
                        </p>
                    </div>
                </div>

                <button type="button" onclick="resetCalculator()" class="text-xs text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors cursor-pointer" title="সবকিছু রিসেট করুন">
                    <span>🔄</span>
                    <span class="font-bold">রিসেট</span>
                </button>
            </div>

            <form id="salaryForm" onsubmit="event.preventDefault(); calculateSalaryPhases();" class="space-y-5">

                <!-- Step 1: Grade Selection -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="grade" class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-bold text-xs shrink-0">১</span>
                            <span>আপনার চাকরির গ্রেড নির্বাচন করুন <span class="text-rose-500">*</span></span>
                        </label>
                        <span class="text-[11px] text-slate-400 font-semibold hidden sm:inline">১ম - ২০তম গ্রেড</span>
                    </div>

                    <!-- Quick Grade Chips -->
                    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar scrollbar-none py-1">
                        <span class="text-[11px] font-bold text-slate-400 shrink-0">জনপ্রিয়:</span>
                        <button type="button" onclick="selectQuickGrade(20, this)" class="quick-grade-btn shrink-0 px-2.5 py-1 rounded-lg hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 font-bold transition-colors text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">২০তম</button>
                        <button type="button" onclick="selectQuickGrade(16, this)" class="quick-grade-btn shrink-0 px-2.5 py-1 rounded-lg hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 font-bold transition-colors text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">১৬তম</button>
                        <button type="button" onclick="selectQuickGrade(13, this)" class="quick-grade-btn shrink-0 px-2.5 py-1 rounded-lg hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 font-bold transition-colors text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">১৩তম</button>
                        <button type="button" onclick="selectQuickGrade(11, this)" class="quick-grade-btn shrink-0 px-2.5 py-1 rounded-lg hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 font-bold transition-colors text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">১১তম</button>
                        <button type="button" onclick="selectQuickGrade(10, this)" class="quick-grade-btn shrink-0 px-2.5 py-1 rounded-lg hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 font-bold transition-colors text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">১০ম</button>
                        <button type="button" onclick="selectQuickGrade(9, this)" class="quick-grade-btn shrink-0 px-2.5 py-1 rounded-lg hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 font-bold transition-colors text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">৯ম (বিসিএস)</button>
                        <button type="button" onclick="selectQuickGrade(1, this)" class="quick-grade-btn shrink-0 px-2.5 py-1 rounded-lg hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 font-bold transition-colors text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">১ম</button>
                    </div>

                    <select id="grade" onchange="onGradeChange()" class="w-full bg-slate-50 dark:bg-slate-800/90 border-2 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-base sm:text-sm font-bold rounded-xl p-3.5 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer" required>
                        <option value="" disabled selected>গ্রেড নির্বাচন করুন (১ম থেকে ২০তম)</option>
                        <option value="1">গ্রেড ১ (সচিব/সমমান)</option><option value="2">গ্রেড ২</option><option value="3">গ্রেড ৩</option><option value="4">গ্রেড ৪</option><option value="5">গ্রেড ৫</option><option value="6">গ্রেড ৬</option><option value="7">গ্রেড ৭</option><option value="8">গ্রেড ৮</option><option value="9">গ্রেড ৯ (বিসিএস/১ম শ্রেণি)</option><option value="10">গ্রেড ১০ (২য় শ্রেণি)</option><option value="11">গ্রেড ১১</option><option value="12">গ্রেড ১২</option><option value="13">গ্রেড ১৩</option><option value="14">গ্রেড ১৪</option><option value="15">গ্রেড ১৫</option><option value="16">গ্রেড ১৬</option><option value="17">গ্রেড ১৭</option><option value="18">গ্রেড ১৮</option><option value="19">গ্রেড ১৯</option><option value="20">গ্রেড ২০</option>
                    </select>
                </div>

                <!-- Step 2: 2015 Basic Pay Step -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="oldBasicPay" class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-bold text-xs shrink-0">২</span>
                            <span>বর্তমান মূল বেতন বা ধাপ (২০১৫ স্কেল) <span class="text-rose-500">*</span></span>
                        </label>
                        <span id="stepHelpNotice" class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold">সার্ভিস বুক বা পে-স্লিপের ধাপ</span>
                    </div>

                    <select id="oldBasicPay" onchange="calculateNewBasic()" class="w-full bg-slate-50 dark:bg-slate-800/90 border-2 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-base sm:text-sm font-bold rounded-xl p-3.5 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" required disabled>
                        <option value="" disabled selected>👈 প্রথমে ওপরের গ্রেড নির্বাচন করুন</option>
                    </select>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">
                        আপনার বর্তমান মূল বেতন সিলেক্ট করলেই নিচে ২০২৬ সালের নতুন মূল বেতন তাৎক্ষণিক হিসাব হবে।
                    </p>
                </div>

                <!-- Instant preview: unified stat strip -->
                <div class="rounded-2xl border-2 border-emerald-300 dark:border-emerald-700/60 bg-emerald-50/50 dark:bg-emerald-950/20 p-4 sm:p-5 space-y-3">
                    <div class="flex items-center justify-between">
                            <span class="text-xs sm:text-sm font-bold text-emerald-800 dark:text-emerald-300 flex items-center gap-1.5">
                                <span>✨</span>
                                <span>নির্ধারিত নতুন মূল বেতন (২০২৬ স্কেল)</span>
                            </span>
                        <span id="scaleRatioBadge" class="text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-600 text-white hidden">
                                ১০০% নিশ্চিত বৃদ্ধি
                            </span>
                    </div>

                    <div class="grid grid-cols-3 divide-x divide-emerald-200 dark:divide-emerald-800/70 border-t border-emerald-200 dark:border-emerald-800/70 pt-3 text-center">
                        <div class="px-1">
                            <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 block font-semibold">পূর্বের বেতন (২০১৫)</span>
                            <span id="previewOldBasic" class="text-sm sm:text-base font-extrabold text-slate-700 dark:text-slate-200 block mt-1">৳০</span>
                        </div>
                        <div class="px-1">
                            <span class="text-[10px] sm:text-[11px] text-emerald-700 dark:text-emerald-400 block font-semibold">নতুন মূল বেতন (২০২৬)</span>
                            <span id="previewNewBasic" class="text-base sm:text-lg font-extrabold text-emerald-600 dark:text-emerald-400 block mt-1">৳০</span>
                        </div>
                        <div class="px-1">
                            <span class="text-[10px] sm:text-[11px] text-teal-700 dark:text-teal-400 block font-semibold">মূল বেতন বৃদ্ধি</span>
                            <span id="previewDiff" class="text-sm sm:text-base font-extrabold text-teal-600 dark:text-teal-400 block mt-1">৳০</span>
                        </div>
                    </div>
                    <input type="hidden" id="newBasicPayVal" value="">
                    <input type="hidden" id="newBasicPayDisplay" value="">

                    <!-- Calculation Logic Accordion (Dynamic Math Table) -->
                    <details open class="group mt-3 bg-white/70 dark:bg-slate-900/50 rounded-xl border border-emerald-200/80 dark:border-emerald-800/60 shadow-sm cursor-pointer [&_summary::-webkit-details-marker]:hidden">
                        <summary class="flex items-center justify-between p-3 text-xs font-bold text-emerald-800 dark:text-emerald-400 select-none">
                                <span class="flex items-center gap-1.5">
                                    <span>💡</span>
                                    <span>কীভাবে এই নতুন বেতন নির্ধারণ হলো? (অঙ্কে বিস্তারিত)</span>
                                </span>
                            <span class="text-emerald-500 font-bold transition-transform duration-300 group-open:rotate-180">▼</span>
                        </summary>
                        <div class="px-3 pb-3 border-t border-emerald-100 dark:border-emerald-800/50 pt-2" id="calculationStepsTable">
                            <div class="text-[11px] text-slate-500 text-center py-2">প্রথমে ওপরের গ্রেড ও বর্তমান মূল বেতন সিলেক্ট করুন।</div>
                        </div>
                    </details>
                </div>

                <!-- Step 3: Allowance calculation parameters -->
                <div class="bg-slate-50/90 dark:bg-slate-800/50 p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-700/70 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700/80 pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-bold text-xs shrink-0">৩</span>
                            <h3 class="font-bold text-xs sm:text-sm text-slate-800 dark:text-slate-200">
                                ভাতা ও কর্মস্থল সংক্রান্ত তথ্য (ঐচ্ছিক)
                            </h3>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">বাড়িভাড়া ও অন্যান্য</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                        <div class="space-y-1">
                            <label for="area" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                কর্মস্থলের এলাকা
                            </label>
                            <select id="area" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 p-3 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                                <option value="dhaka">ঢাকা উত্তর ও দক্ষিণ সিটি কর্পোরেশন</option>
                                <option value="other_city">অন্যান্য সিটি কর্পোরেশন, সাভার ও কক্সবাজার</option>
                                <option value="others" selected>অন্যান্য জেলা ও উপজেলা সদর</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="children" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                সন্তান সংখ্যা (শিক্ষা ভাতার জন্য)
                            </label>
                            <select id="children" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 p-3 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                                <option value="0">নেই (০ জন)</option>
                                <option value="1">১ জন সন্তান (৳৫০০)</option>
                                <option value="2">২ জন বা ততোধিক সন্তান (৳১০০০)</option>
                            </select>
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <label for="age" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                কর্মচারীর বয়স (চিকিৎসা ভাতার জন্য)
                            </label>
                            <select id="age" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 p-3 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                                <option value="under50">৫০ বছর বা তার কম (৳১৫০০)</option>
                                <option value="over50">৫০ বছরের বেশি (৳২৫০০)</option>
                            </select>
                        </div>

                        <label for="cityTransport" class="flex items-center justify-between p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-emerald-500 cursor-pointer transition-colors select-none">
                            <div class="space-y-0.5 pr-2">
                                <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 block">সিটি যাতায়াত ভাতা?</span>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block">সরকারি পরিবহন না পেলে প্রযোজ্য</span>
                            </div>
                            <input type="checkbox" id="cityTransport" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-600 shrink-0 cursor-pointer">
                        </label>

                        <label for="tiffinProvided" class="flex items-center justify-between p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-emerald-500 cursor-pointer transition-colors select-none">
                            <div class="space-y-0.5 pr-2">
                                <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 block">অফিসে খাবার / লাঞ্চ পান?</span>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block">টিক দিলে টিফিন ভাতা বাদ যাবে</span>
                            </div>
                            <input type="checkbox" id="tiffinProvided" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-600 shrink-0 cursor-pointer">
                        </label>
                    </div>
                </div>

                <button type="submit" id="calcBtn" class="w-full py-4 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-base sm:text-lg shadow-sm active:scale-[0.98] transition-all flex items-center justify-center gap-2.5 cursor-pointer">
                    <span class="text-xl">📊</span>
                    <span>৪টি ধাপের বিস্তারিত বেতন ও ভাতা হিসাব করুন</span>
                </button>
            </form>
        </div>

        <!-- Right column -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Dark stat panel -->
            <div class="bg-emerald-50/40 dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-800 text-slate-800 dark:text-white p-6 sm:p-7 rounded-2xl border border-emerald-200/60 dark:border-slate-700/80 space-y-5">
                <div class="flex items-center justify-between border-b border-emerald-200/80 dark:border-slate-700 pb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">বেতন বৃদ্ধি প্রাক্কলন</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">২০১৫ ➔ ২০২৬</span>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-300">পূর্বের মূল বেতন:</span>
                        <span id="sideOldBasic" class="font-bold text-base sm:text-lg text-slate-800 dark:text-white">৳ ০</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-300">নতুন মূল বেতন:</span>
                        <span id="sideNewBasic" class="font-extrabold text-lg sm:text-xl text-emerald-700 dark:text-emerald-400">৳ ০</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-emerald-200/80 dark:border-slate-700">
                        <span class="text-xs sm:text-sm text-emerald-800 dark:text-emerald-200 font-bold">মোট মূল বেতন বৃদ্ধি:</span>
                        <span id="sideDiff" class="font-extrabold text-base sm:text-lg text-emerald-700 dark:text-emerald-300">৳ ০</span>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-white/5 border border-amber-200/80 dark:border-white/10 text-xs text-amber-900 dark:text-slate-300 leading-relaxed">
                    💡 <strong>জরুরি তথ্য:</strong> প্রস্তাবিত পে স্কেলে ১ম থেকে ২০তম গ্রেডের কর্মচারীদের জন্য ধাপে ধাপে ১০০% বৃদ্ধি সমন্বিত হবে এবং চূড়ান্ত ধাপে বাড়িভাড়াসহ সকল ভাতা নতুন স্কেলে প্রযোজ্য হবে।
                </div>
            </div>

            <!-- 4-phase timeline -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                    <span>🗓️</span>
                    <span>বাস্তবায়নের ৪টি পর্যায়কাল</span>
                </h3>
                <ul class="relative pl-7 space-y-4 before:content-[''] before:absolute before:left-[11px] before:top-1 before:bottom-1 before:w-px before:bg-slate-200 dark:before:bg-slate-700">
                    <li class="relative">
                        <span class="absolute -left-7 top-0 w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-[10px]">১</span>
                        <div class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed"><strong class="text-slate-800 dark:text-slate-100">১ম ধাপ (১ জুলাই ২০২৬):</strong> গ্রেড ১-৯ এ ৪০% এবং গ্রেড ১০-২০ এ ৫০% বর্ধিত বেতন।</div>
                    </li>
                    <li class="relative">
                        <span class="absolute -left-7 top-0 w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-[10px]">২</span>
                        <div class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed"><strong class="text-slate-800 dark:text-slate-100">২য় ধাপ (১ জানুয়ারি ২০২৭):</strong> গ্রেড ১-৯ এ ৭০% এবং গ্রেড ১০-২০ এ ৭৫% বর্ধিত বেতন।</div>
                    </li>
                    <li class="relative">
                        <span class="absolute -left-7 top-0 w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-[10px]">৩</span>
                        <div class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed"><strong class="text-slate-800 dark:text-slate-100">৩য় ধাপ (১ জুলাই ২০২৭):</strong> সকল গ্রেডে নতুন মূল বেতনের ১০০% কার্যকর।</div>
                    </li>
                    <li class="relative">
                        <span class="absolute -left-7 top-0 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-[10px]">৪</span>
                        <div class="text-xs text-slate-700 dark:text-slate-200 leading-relaxed"><strong class="text-emerald-700 dark:text-emerald-400">চূড়ান্ত ধাপ (১ জানুয়ারি ২০২৮):</strong> নতুন মূল বেতনের ওপর নতুন হারে সকল ভাতা কার্যকর।</div>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Results Section -->
    <div id="result" class="space-y-6 pt-2 hidden">

        <!-- Summary -->
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-7 rounded-2xl border-2 border-emerald-500/80 dark:border-emerald-500/60 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📊</span>
                    <h3 class="font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white">
                        মূল বেতনের তুলনামূলক বিবরণী
                    </h3>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                    <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="px-3 py-1.5 rounded-lg bg-[#25D366] hover:bg-[#20bd5a] text-white text-xs font-bold flex items-center gap-1.5 transition-colors" title="WhatsApp-এ শেয়ার করুন">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"></path></svg>
                        <span>WhatsApp</span>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="px-3 py-1.5 rounded-lg bg-[#1877F2] hover:bg-[#166fe5] text-white text-xs font-bold flex items-center gap-1.5 transition-colors" title="ফেসবুকে শেয়ার করুন">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
                        <span>ফেসবুক</span>
                    </a>
                    <button type="button" onclick="copySummary()" class="px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-colors">
                        <span>📋</span>
                        <span id="copyBtnText">সারাংশ কপি</span>
                    </button>
                    <button type="button" onclick="window.print()" class="px-3.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 font-bold text-xs flex items-center gap-1.5 transition-colors">
                        <span>🖨️</span>
                        <span>প্রিন্ট</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-3 divide-x divide-slate-200 dark:divide-slate-800 text-center">
                <div class="px-2">
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-bold">পূর্বের মূল বেতন (২০১৫)</div>
                    <div class="font-extrabold text-xl sm:text-2xl text-slate-800 dark:text-slate-100 mt-1" id="resOldBasic">৳ ২০,৪৪০</div>
                </div>
                <div class="px-2">
                    <div class="text-xs text-emerald-700 dark:text-emerald-400 font-bold">নির্ধারিত নতুন মূল বেতন (২০২৬)</div>
                    <div class="font-extrabold text-xl sm:text-2xl text-emerald-600 dark:text-emerald-400 mt-1" id="resNewBasic">৳ ৩৭,১০০</div>
                </div>
                <div class="px-2">
                    <div class="text-xs text-teal-700 dark:text-teal-400 font-bold">মোট বেতন বৃদ্ধি (পার্থক্য)</div>
                    <div class="font-extrabold text-xl sm:text-2xl text-teal-600 dark:text-teal-400 mt-1" id="resDiff">+ ৳ ১৬,৬৬০</div>
                </div>
            </div>
        </div>

        <div class="text-center pt-2">
            <h3 class="font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white flex items-center justify-center gap-2">
                <span>🗓️</span>
                <span>৪টি ধাপের বিস্তারিত বেতন ও ভাতাসমূহ</span>
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                প্রতিটি ধাপে আপনার একাউন্টে প্রতি মাসে জমাকৃত মূল বেতন, ভাতা ও মোট টাকার হিসাব
            </p>
        </div>

        <!-- 4-Phases Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

            <!-- Phase 1 (Blue Theme) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border-2 border-blue-200 dark:border-blue-900/60 p-5 flex flex-col justify-between relative shadow-sm">
                <span class="absolute -top-2.5 right-4 bg-blue-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">১ম বাস্তবায়ন</span>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 flex items-center justify-center font-bold text-[11px] shrink-0">১</span>
                        <h4 class="font-extrabold text-blue-900 dark:text-blue-300 text-base">১ম পর্যায়</h4>
                    </div>
                    <p class="text-xs text-blue-600 dark:text-blue-400/80 mb-3 ml-8">জুলাই ২০২৬ - ডিসে ২০২৬</p>

                    <div class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300">
                        <div class="flex justify-between border-b border-blue-100 dark:border-blue-900/50 pb-1.5 mb-1.5">
                            <span class="font-bold">মূল বেতন:</span>
                            <span id="p1Basic" class="font-extrabold text-slate-900 dark:text-white">৳ ২৮,৭৭০</span>
                        </div>
                        <div id="p1AllowancesList">
                            <div class="text-[11px] space-y-1 mt-2 p-2.5 bg-blue-50/50 dark:bg-slate-800/60 rounded-xl border border-blue-100/50 dark:border-slate-700/60">
                                <div class="text-slate-500 dark:text-slate-400 mb-1 border-b border-slate-200 dark:border-slate-700 pb-1 font-bold">ভাতাসমূহ (২০১৫ হারে):</div>
                                <div class="flex justify-between"><span>বাড়িভাড়া (৫৫%):</span><span class="font-bold">৳ ১১,২৪২</span></div>
                                <div class="flex justify-between"><span>চিকিৎসা:</span><span class="font-bold">৳ ১,৫০০</span></div>
                                <div class="flex justify-between"><span>শিক্ষা ভাতা:</span><span class="font-bold">৳ ৫০০</span></div>
                                <div class="flex justify-between mt-1 pt-1 border-t border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-extrabold"><span>মোট ভাতা:</span><span>৳ ১৩,২৪২</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t-2 border-blue-200 dark:border-blue-900/60">
                    <div class="text-[11px] text-blue-700 dark:text-blue-400 font-bold mb-0.5">সর্বমোট বেতন (মাসিক):</div>
                    <div class="text-xl font-extrabold text-blue-700 dark:text-blue-400" id="p1Gross">৳ ৪২,০১২</div>
                </div>
            </div>

            <!-- Phase 2 (Purple Theme) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border-2 border-purple-200 dark:border-purple-900/60 p-5 flex flex-col justify-between shadow-sm">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 flex items-center justify-center font-bold text-[11px] shrink-0">২</span>
                        <h4 class="font-extrabold text-purple-900 dark:text-purple-300 text-base">২য় পর্যায়</h4>
                    </div>
                    <p class="text-xs text-purple-600 dark:text-purple-400/80 mb-3 ml-8">জানু ২০২৭ - জুন ২০২৭</p>

                    <div class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300">
                        <div class="flex justify-between border-b border-purple-100 dark:border-purple-900/50 pb-1.5 mb-1.5">
                            <span class="font-bold">মূল বেতন:</span>
                            <span id="p2Basic" class="font-extrabold text-slate-900 dark:text-white">৳ ৩২,۹৩৫</span>
                        </div>
                        <div id="p2AllowancesList">
                            <div class="text-[11px] space-y-1 mt-2 p-2.5 bg-purple-50/50 dark:bg-slate-800/60 rounded-xl border border-purple-100/50 dark:border-slate-700/60">
                                <div class="text-slate-500 dark:text-slate-400 mb-1 border-b border-slate-200 dark:border-slate-700 pb-1 font-bold">ভাতাসমূহ (২০১৫ হারে):</div>
                                <div class="flex justify-between"><span>বাড়িভাড়া (৫৫%):</span><span class="font-bold">৳ ১১,২৪২</span></div>
                                <div class="flex justify-between"><span>চিকিৎসা:</span><span class="font-bold">৳ ১,৫০০</span></div>
                                <div class="flex justify-between"><span>শিক্ষা ভাতা:</span><span class="font-bold">৳ ৫০০</span></div>
                                <div class="flex justify-between mt-1 pt-1 border-t border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-extrabold"><span>মোট ভাতা:</span><span>৳ ১৩,২৪২</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t-2 border-purple-200 dark:border-purple-900/60">
                    <div class="text-[11px] text-purple-700 dark:text-purple-400 font-bold mb-0.5">সর্বমোট বেতন (মাসিক):</div>
                    <div class="text-xl font-extrabold text-purple-700 dark:text-purple-400" id="p2Gross">৳ ৪৬,১৭৭</div>
                </div>
            </div>

            <!-- Phase 3 (Teal Theme) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border-2 border-teal-200 dark:border-teal-900/60 p-5 flex flex-col justify-between shadow-sm">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-6 h-6 rounded-full bg-teal-100 dark:bg-teal-900/50 text-teal-700 dark:text-teal-300 flex items-center justify-center font-bold text-[11px] shrink-0">৩</span>
                        <h4 class="font-extrabold text-teal-900 dark:text-teal-300 text-base">৩য় পর্যায়</h4>
                    </div>
                    <p class="text-xs text-teal-600 dark:text-teal-400/80 mb-3 ml-8">জুলাই ২০২৭ - ডিসে ২০২৭</p>

                    <div class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300">
                        <div class="flex justify-between border-b border-teal-100 dark:border-teal-900/50 pb-1.5 mb-1.5">
                            <span class="font-bold">মূল বেতন (১০০%):</span>
                            <span id="p3Basic" class="font-extrabold text-slate-900 dark:text-white">৳ ৩৭,১০০</span>
                        </div>
                        <div id="p3AllowancesList">
                            <div class="text-[11px] space-y-1 mt-2 p-2.5 bg-teal-50/50 dark:bg-slate-800/60 rounded-xl border border-teal-100/50 dark:border-slate-700/60">
                                <div class="text-slate-500 dark:text-slate-400 mb-1 border-b border-slate-200 dark:border-slate-700 pb-1 font-bold">ভাতাসমূহ (২০১৫ হারে):</div>
                                <div class="flex justify-between"><span>বাড়িভাড়া (৫৫%):</span><span class="font-bold">৳ ১১,২৪২</span></div>
                                <div class="flex justify-between"><span>চিকিৎসা:</span><span class="font-bold">৳ ১,৫০০</span></div>
                                <div class="flex justify-between"><span>শিক্ষা ভাতা:</span><span class="font-bold">৳ ৫০০</span></div>
                                <div class="flex justify-between mt-1 pt-1 border-t border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-extrabold"><span>মোট ভাতা:</span><span>৳ ১৩,২৪২</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t-2 border-teal-200 dark:border-teal-900/60">
                    <div class="text-[11px] text-teal-700 dark:text-teal-400 font-bold mb-0.5">সর্বমোট বেতন (মাসিক):</div>
                    <div class="text-xl font-extrabold text-teal-700 dark:text-teal-400" id="p3Gross">৳ ৫০,৩৪২</div>
                </div>
            </div>

            <!-- Phase 4 (Emerald/Green Theme - Final) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border-2 border-emerald-500 p-5 flex flex-col justify-between relative shadow-sm">
                <span class="absolute -top-2.5 right-4 bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">✨ নতুন ভাতাসহ পূর্ণাঙ্গ</span>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-[11px] shrink-0">৪</span>
                        <h4 class="font-extrabold text-emerald-700 dark:text-emerald-400 text-base">চূড়ান্ত ধাপ</h4>
                    </div>
                    <p class="text-xs text-emerald-600 dark:text-emerald-500/80 mb-3 ml-8 font-semibold">১ জানু ২০২৮ থেকে চলমান</p>

                    <div class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300">
                        <div class="flex justify-between border-b border-emerald-200 dark:border-emerald-800/60 pb-1.5 mb-1.5">
                            <span class="font-bold">মূল বেতন (১০০%):</span>
                            <span id="p4Basic" class="font-extrabold text-slate-900 dark:text-white">৳ ৩৭,১০০</span>
                        </div>
                        <div id="p4AllowancesList">
                            <div class="text-[11px] space-y-1 mt-2 p-2.5 bg-emerald-50/70 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800/60">
                                <div class="text-emerald-800 dark:text-emerald-400 mb-1 border-b border-emerald-200 dark:border-emerald-800/60 pb-1 font-bold">ভাতাসমূহ (২০২৮ নতুন হারে):</div>
                                <div class="flex justify-between"><span>নতুন বাড়িভাড়া (৫০%):</span><span class="font-bold">৳ ১৮,৫৫০</span></div>
                                <div class="flex justify-between"><span>নতুন চিকিৎসা:</span><span class="font-bold">৳ ৩,০০০</span></div>
                                <div class="flex justify-between"><span>শিক্ষা ভাতা:</span><span class="font-bold">৳ ৫০০</span></div>
                                <div class="flex justify-between"><span>টিফিন ভাতা:</span><span class="font-bold">৳ ৫০০</span></div>
                                <div class="flex justify-between"><span>যাতায়াত ভাতা:</span><span class="font-bold">৳ ৬০০</span></div>
                                <div class="flex justify-between"><span>মোবাইল ভাতা:</span><span class="font-bold">৳ ১৫০</span></div>
                                <div class="flex justify-between mt-1 pt-1 border-t border-emerald-300 dark:border-emerald-700/60 text-emerald-900 dark:text-emerald-200 font-extrabold"><span>নতুন মোট ভাতা:</span><span>৳ ২২,২০০</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t-2 border-emerald-500">
                    <div class="text-[11px] text-emerald-700 dark:text-emerald-400 font-bold mb-0.5">চূড়ান্ত সর্বমোট বেতন:</div>
                    <div class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400" id="p4Gross">৳ ৫৯,৩০০</div>
                </div>
            </div>

        </div>

        <!-- NEW SECTION HERE -->
        <!-- Arrears & Annual Allowances -->
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-7 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
            <h3 class="font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                <span class="text-xl">🎁</span>
                <span>বকেয়া ও বার্ষিক ভাতা</span>
            </h3>
            <div id="arrearsSection" class="space-y-0 text-sm">
                <!-- Populated via JS -->
            </div>
        </div>

        <div class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/60 leading-relaxed">
            <strong class="text-slate-700 dark:text-slate-200">বিশেষ দ্রষ্টব্য:</strong> ৩১ ডিসেম্বর ২০২৭ পর্যন্ত সকল ভাতা ২০১৫ সালের প্রচলিত নিয়ম অনুযায়ী পুরাতন মূল বেতনের ওপর ভিত্তি করে প্রদর্শিত হয়েছে। ১ জানুয়ারি ২০২৮ থেকে নতুন স্কেলের মূল বেতনের ওপর প্রস্তাবিত প্রজ্ঞাপনের হারে সকল নতুন ভাতা হিসাব করা হয়েছে।
        </div>

    </div>

    <!-- SEO Content -->
    <div class="space-y-6 sm:space-y-8 pt-4">

        <!-- Grade comparison table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>📋</span>
                        <span>১ম থেকে ২০তম গ্রেড: জাতীয় বেতন স্কেল ২০১৫ বনাম ২০২৬ প্রারম্ভিক বেতন</span>
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        প্রতিটি গ্রেডের ২০১৫ স্কেলের শুরুর মূল বেতন এবং ২০২৬ স্কেলের প্রস্তাবিত শুরুর বেতনের তুলনা
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 font-bold border-b border-slate-200 dark:border-slate-700">
                        <th class="py-3 px-3 sm:px-4">গ্রেড</th>
                        <th class="py-3 px-3 sm:px-4">২০১৫ স্কেল (প্রারম্ভিক)</th>
                        <th class="py-3 px-3 sm:px-4">২০২৬ স্কেল (প্রস্তাবিত প্রারম্ভিক)</th>
                        <th class="py-3 px-3 sm:px-4">বৃদ্ধির পরিমাণ</th>
                        <th class="py-3 px-3 sm:px-4">শতকরা বৃদ্ধি</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 font-semibold"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১</td><td class="py-2.5 px-3 sm:px-4">৳ ৭৮,০০০ (নির্ধারিত)</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 dark:text-emerald-400 font-bold">৳ ১,৫৬,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ৭৮,০০০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 font-bold">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ২</td><td class="py-2.5 px-3 sm:px-4">৳ ৬৬,০০০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 dark:text-emerald-400 font-bold">৳ ১,৩২,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ৬৬,০০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ৩</td><td class="py-2.5 px-3 sm:px-4">৳ ৫৬,৫০০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 dark:text-emerald-400 font-bold">৳ ১,১৩,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ৫৬,৫০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ৪</td><td class="py-2.5 px-3 sm:px-4">৳ ৫০,০০০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 dark:text-emerald-400 font-bold">৳ ১,০০,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ৫০,০০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ৫</td><td class="py-2.5 px-3 sm:px-4">৳ ৪৩,০০০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 dark:text-emerald-400 font-bold">৳ ৮৬,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ۴৩,০০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ৬</td><td class="py-2.5 px-3 sm:px-4">৳ ৩৫,৫০০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 dark:text-emerald-400 font-bold">৳ ৭১,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ৩৫,৫০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ৭</td><td class="py-2.5 px-3 sm:px-4">৳ ২৯,০০০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 dark:text-emerald-400 font-bold">৳ ৫৮,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ২৯,০০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ৮</td><td class="py-2.5 px-3 sm:px-4">৳ ২৩,০০০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 dark:text-emerald-400 font-bold">৳ ৪৬,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ২৩,০০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 font-semibold text-emerald-800 dark:text-emerald-300 bg-emerald-50/40 dark:bg-emerald-950/20"><td class="py-2.5 px-3 sm:px-4">গ্রেড ৯ (বিসিএস)</td><td class="py-2.5 px-3 sm:px-4">৳ ২২,০০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ৪৪,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ২২,০০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 font-semibold"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১০</td><td class="py-2.5 px-3 sm:px-4">৳ ১৬,০০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ৩২,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১৬,০০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১১</td><td class="py-2.5 px-3 sm:px-4">৳ ১২,৫০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২৫,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১২,৫০০</td><td class="py-2.5 px-3 sm:px-4">১০০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১২</td><td class="py-2.5 px-3 sm:px-4">৳ ১১,৩০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২৪,৩০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১৩,০০০</td><td class="py-2.5 px-3 sm:px-4">১১৫%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১৩</td><td class="py-2.5 px-3 sm:px-4">৳ ১১,০০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২৪,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১৩,০০০</td><td class="py-2.5 px-3 sm:px-4">১১৮%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১৪</td><td class="py-2.5 px-3 sm:px-4">৳ ১০,২০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২৩,৫০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১৩,৩০০</td><td class="py-2.5 px-3 sm:px-4">১৩০%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১৫</td><td class="py-2.5 px-3 sm:px-4">৳ ৯,৭০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২২,৮০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১৩,১০০</td><td class="py-2.5 px-3 sm:px-4">১৩৫%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১৬</td><td class="py-2.5 px-3 sm:px-4">৳ ৯,৩০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২১,৯০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১২,৬০০</td><td class="py-2.5 px-3 sm:px-4">১৩৫%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১৭</td><td class="py-2.5 px-3 sm:px-4">৳ ৯,০০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২১,৪০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১২,৪০০</td><td class="py-2.5 px-3 sm:px-4">১৩৭%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১৮</td><td class="py-2.5 px-3 sm:px-4">৳ ۸,৮০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২১,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১২,২০০</td><td class="py-2.5 px-3 sm:px-4">১৩৮%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"><td class="py-2.5 px-3 sm:px-4">গ্রেড ১৯</td><td class="py-2.5 px-3 sm:px-4">৳ ৮,৫০০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২০,৫০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১২,০০০</td><td class="py-2.5 px-3 sm:px-4">১৪১%</td></tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 font-semibold bg-emerald-50/30 dark:bg-emerald-950/20"><td class="py-2.5 px-3 sm:px-4">গ্রেড ২০</td><td class="py-2.5 px-3 sm:px-4">৳ ৮,২৫০</td><td class="py-2.5 px-3 sm:px-4 font-bold text-emerald-600 dark:text-emerald-400">৳ ২০,০০০</td><td class="py-2.5 px-3 sm:px-4">৳ ১১,৭৫০</td><td class="py-2.5 px-3 sm:px-4 text-emerald-600 font-bold">১৪২%</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Guide cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 space-y-4">
                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span>🏠</span>
                    <span>বাড়িভাড়া ভাতার নতুন কাঠামো ও হার</span>
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    ২০২৮ সালের চূড়ান্ত ধাপে বাড়িভাড়া ভাতার ক্ষেত্রে পদমর্যাদা ও কর্মস্থলের ভৌগোলিক অবস্থান অনুযায়ী নতুন শতকরা হার প্রস্তাব করা হয়েছে:
                </p>
                <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300">
                    <li class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60">
                        <strong>১৬তম থেকে ২০তম গ্রেড:</strong> ঢাকা সিটিতে ৬০%, অন্যান্য সিটিতে ৫০% এবং অন্যান্য স্থানে ৪৫%।
                    </li>
                    <li class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60">
                        <strong>১০ম থেকে ১৫তম গ্রেড:</strong> ঢাকা সিটিতে ৫০%, অন্যান্য সিটিতে ৪০% এবং অন্যান্য স্থানে ৩৫%।
                    </li>
                    <li class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60">
                        <strong>৫ম থেকে ৯ম গ্রেড:</strong> ঢাকা সিটিতে ৪৫%, অন্যান্য সিটিতে ৩৫% এবং অন্যান্য স্থানে ৩০%।
                    </li>
                    <li class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60">
                        <strong>১ম থেকে ৪র্থ গ্রেড:</strong> ঢাকা সিটিতে ৪০%, অন্যান্য সিটিতে ৩০% এবং অন্যান্য স্থানে ২৫%।
                    </li>
                </ul>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 space-y-4">
                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span>🏥</span>
                    <span>চিকিৎসা, শিক্ষা ও অন্যান্য ভাতা</span>
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    প্রস্তাবিত বেতন স্কেলে জীবনযাত্রার ব্যয় বৃদ্ধির কথা বিবেচনা করে সকল ক্যাটাগরির ভাতায় ব্যাপক পরিবর্তন আনা হচ্ছে:
                </p>
                <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300">
                    <li class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60">
                        <strong>চিকিৎসা ভাতা:</strong> ৫০ বছর বা তার কম বয়সীদের জন্য ৩,০০০ টাকা এবং ৫০ বছরের বেশি বয়সীদের জন্য ৪,০০০ টাকা।
                    </li>
                    <li class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60">
                        <strong>শিক্ষা সহায়ক ভাতা:</strong> সন্তান প্রতি ৫০০ টাকা করে সর্বোচ্চ ২ সন্তানের জন্য ১,০০০ টাকা।
                    </li>
                    <li class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60">
                        <strong>টিফিন ও যাতায়াত ভাতা:</strong> ১১-২০তম গ্রেডের জন্য টিফিন ভাতা ৫০০ টাকা এবং সিটি কর্পোরেশনে যাতায়াত ভাতা ৬০০ টাকা।
                    </li>
                    <li class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60">
                        <strong>মোবাইল ভাতা:</strong> ১ম থেকে ৫ম গ্রেড ৫০০ টাকা এবং অন্যান্য গ্রেড ১৫০ টাকা।
                    </li>
                </ul>
            </div>

        </div>

        <!-- FAQ -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 space-y-4">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                <span>❓</span>
                <span>৯ম পে স্কেল সম্পর্কিত সচরাচর জিজ্ঞাসা (FAQ)</span>
            </h2>

            <div class="space-y-3 pt-1">
                <details class="group p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-700/70 cursor-pointer">
                    <summary class="font-bold text-sm text-slate-800 dark:text-slate-200 flex items-center justify-between">
                        <span>জাতীয় বেতন স্কেল ২০২৬ কীভাবে হিসাব করা হয়েছে?</span>
                        <span class="text-emerald-600 transition-transform group-open:rotate-180">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-3 leading-relaxed border-t border-slate-200/60 dark:border-slate-700 pt-2.5">
                        ২০১৫ সালের মূল বেতনের ওপর ভিত্তি করে ২০২৬ সালের প্রস্তাবিত গ্রেড অনুযায়ী সমপরিমাণ বা নিকটবর্তী পরবর্তী ইনক্রিমেন্ট ধাপটি নির্ধারণ করা হয়েছে। এরপর গ্রেড ১-৯ এবং ১০-২০ এর নির্ধারিত শতকরা হারে ৪টি ধাপে মোট বৃদ্ধির অংক যোগ করা হয়েছে।
                    </p>
                </details>

                <details class="group p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-700/70 cursor-pointer">
                    <summary class="font-bold text-sm text-slate-800 dark:text-slate-200 flex items-center justify-between">
                        <span>পেনশনার ও অবসরপ্রাপ্ত কর্মকর্তাদের জন্য কি এটি প্রযোজ্য?</span>
                        <span class="text-emerald-600 transition-transform group-open:rotate-180">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-3 leading-relaxed border-t border-slate-200/60 dark:border-slate-700 pt-2.5">
                        হ্যাঁ, নতুন পে স্কেল কার্যকর হলে অবসরপ্রাপ্ত সরকারি চাকরিজীবীদের পেনশন এবং আনুতোষিক (গ্র্যাচুইটি) নতুন বেতন স্কেলের মানদণ্ড অনুযায়ী আনুপাতিক হারে স্বয়ংক্রিয়ভাবে বৃদ্ধি পাবে।
                    </p>
                </details>

                <details class="group p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-700/70 cursor-pointer" open>
                    <summary class="font-bold text-sm text-slate-800 dark:text-slate-200 flex items-center justify-between">
                        <span>সরকারি চূড়ান্ত গেজেটের সাথে এই হিসাবের কোনো অমিল হতে পারে কি?</span>
                        <span class="text-emerald-600 transition-transform group-open:rotate-180">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-3 leading-relaxed border-t border-slate-200/60 dark:border-slate-700 pt-2.5">
                        এই ক্যালকুলেটরটি পে স্কেল কমিশনের বিভিন্ন খসড়া সুপারিশ এবং বিগত জাতীয় বেতন স্কেল বাস্তবায়নের সূত্রের ওপর ভিত্তি করে তৈরি। অর্থ মন্ত্রণালয় কর্তৃক চূড়ান্ত গেজেট প্রজ্ঞাপন জারি করা হলে তার সাথে সামান্য তারতম্য হতে পারে, যা আমরা সাথে সাথে আপডেট করে দেব।
                    </p>
                </details>
            </div>
        </div>

        <!-- Disclaimer -->
        <div class="p-5 sm:p-6 rounded-2xl bg-amber-50/80 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/70 text-amber-900 dark:text-amber-200 space-y-2">
            <div class="flex items-start gap-3">
                <span class="text-2xl shrink-0 mt-0.5">⚠️</span>
                <div class="space-y-1">
                    <h4 class="font-bold text-sm sm:text-base text-amber-950 dark:text-amber-100">
                        তথ্যের নির্ভুলতা ও সতর্কতা বিজ্ঞপ্তি:
                    </h4>
                    <p class="text-xs sm:text-sm leading-relaxed text-amber-800 dark:text-amber-300">
                        এই ক্যালকুলেটরে প্রদর্শিত তথ্য ও হিসাবসমূহ প্রস্তাবিত ৯ম পে স্কেল সংক্রান্ত বিভিন্ন প্রাথমিক প্রতিবেদন, খসড়া সুপারিশ ও প্রচলিত সূত্রের ওপর ভিত্তি করে প্রাক্কলন করা হয়েছে। তথ্যে ভুল ত্রুটি থাকতে পারে, আমরা নিয়মিত সংশোধন করার চেষ্টা করছি। সরকারি চূড়ান্ত গেজেট বা প্রজ্ঞাপন প্রকাশের পর আমরা এটি আরও নিখুঁতভাবে হালনাগাদ করব। কোনো দাপ্তরিক, প্রশাসনিক বা আর্থিক সিদ্ধান্তের ক্ষেত্রে সরকারের চূড়ান্ত প্রজ্ঞাপন ও সংশ্লিষ্ট কর্তৃপক্ষের নির্দেশনাই মান্য হবে।
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@push('scripts')
    <script>
        // ১. ২০১৫ সালের পে-স্কেল অনুযায়ী প্রতিটি গ্রেডের বেসিক বেতনের ধাপ
        const payScales2015 = {
            1: [78000],
            2: [66000, 69300, 72770, 76410],
            3: [56500, 58760, 61120, 63570, 66120, 68770, 71530, 74400],
            4: [50000, 52000, 54080, 56250, 58500, 60840, 63280, 65820, 68460, 71200],
            5: [43000, 44940, 46970, 49090, 51300, 53610, 56030, 58560, 61200, 63960, 66840, 69850],
            6: [35500, 37280, 39150, 41110, 43170, 45330, 47600, 49980, 52480, 55110, 57870, 60770, 63810, 67010],
            7: [29000, 30450, 31980, 33580, 35260, 37030, 38890, 40840, 42890, 45040, 47300, 49670, 52160, 54770, 57510],
            8: [23000, 24150, 25360, 26630, 27970, 29370, 30840, 32390, 34010, 35720, 37510, 39390, 41360, 43430, 45610, 47900, 50300, 52820, 55470],
            9: [22000, 23100, 24260, 25480, 26760, 28100, 29510, 30990, 32540, 34170, 35880, 37680, 39570, 41550, 43630, 45820, 48120, 50530, 53060],
            10: [16000, 16800, 17640, 18530, 19460, 20440, 21470, 22550, 23680, 24870, 26120, 27430, 28810, 30260, 31780, 33370, 35040, 36800, 38640],
            11: [12500, 13130, 13790, 14480, 15210, 15980, 16780, 17620, 18510, 19440, 20420, 21450, 22530, 23660, 24850, 26100, 27410, 28790, 30230, 32240],
            12: [11300, 11870, 12470, 13100, 13760, 14450, 15180, 15940, 16740, 17580, 18460, 19390, 20360, 21380, 22450, 23580, 24760, 26000, 27300],
            13: [11000, 11550, 12130, 12740, 13380, 14050, 14760, 15500, 16280, 17100, 17960, 18860, 19810, 20810, 21860, 22960, 24110, 25320, 26590],
            14: [10200, 10710, 11250, 11820, 12420, 13050, 13710, 14400, 15120, 15880, 16680, 17520, 18400, 19320, 20290, 21310, 22380, 23500, 24680],
            15: [9700, 10190, 10700, 11240, 11810, 12410, 13040, 13700, 14390, 15110, 15870, 16670, 17510, 18390, 19310, 20280, 21300, 22370, 23490],
            16: [9300, 9770, 10260, 10780, 11320, 11890, 12490, 13120, 13780, 14470, 15200, 15960, 16760, 17600, 18480, 19410, 20390, 21410, 22490],
            17: [9000, 9450, 9930, 10430, 10960, 11510, 12090, 12700, 13340, 14010, 14720, 15460, 16240, 17060, 17920, 18820, 19770, 20760, 21800],
            18: [8800, 9240, 9710, 10200, 10710, 11250, 11820, 12420, 13050, 13710, 14400, 15120, 15880, 16680, 17520, 18400, 19320, 20290, 21310],
            19: [8500, 8930, 9380, 9850, 10350, 10870, 11420, 12000, 12600, 13230, 13900, 14600, 15330, 16100, 16910, 17760, 18650, 19590, 20570],
            20: [8250, 8670, 9110, 9570, 10050, 10560, 11090, 11650, 12240, 12860, 13510, 14190, 14900, 15650, 16440, 17270, 18140, 19050, 20010]
        };

        // ২. গেজেট অনুযায়ী ২০২৬ সালের নতুন পে-স্কেলের সকল ধাপ
        const payScales2026 = {
            1: [156000],
            2: [132000, 135700, 139400, 143200, 147200, 151200, 153000],
            3: [113000, 117000, 121100, 125300, 129700, 134300, 139000, 143800, 148800],
            4: [100000, 103500, 107200, 110900, 114800, 118800, 123000, 127300, 131700, 136300, 142400],
            5: [86000, 89500, 93100, 96800, 100700, 104700, 108900, 113200, 117700, 122500, 127400, 132400, 139700],
            6: [71000, 74600, 78300, 82200, 86400, 90700, 95200, 100000, 104900, 110200, 115700, 121500, 127600, 134000],
            7: [58000, 60900, 64000, 67200, 70600, 74100, 77800, 81700, 85800, 90100, 94600, 99300, 104300, 109500, 114900, 120600, 126600],
            8: [46000, 48300, 50800, 53300, 56000, 58800, 61700, 64800, 68000, 71400, 75000, 78700, 82700, 86800, 91100, 95700, 100500, 105500, 110800],
            9: [44000, 46200, 48600, 51000, 53500, 56200, 59000, 62100, 65300, 68600, 71700, 75300, 79100, 83000, 87200, 91500, 96100, 100900, 105900],
            10: [32000, 33600, 35300, 37100, 38900, 40900, 42900, 45100, 47300, 49700, 52200, 54800, 57500, 60400, 63400, 66600, 69900, 73400, 77300],
            11: [25000, 26300, 27600, 29000, 30400, 32000, 33600, 35200, 37000, 38800, 40800, 42800, 44900, 47200, 49500, 52000, 54600, 57300, 60500],
            12: [24300, 25500, 26800, 28200, 29600, 31100, 32600, 34200, 36000, 37700, 39600, 41600, 43700, 45900, 48200, 50600, 53100, 55700, 58700],
            13: [24000, 25200, 26500, 27800, 29200, 30700, 32200, 33800, 35500, 37300, 39100, 41100, 43200, 45300, 47600, 49900, 52400, 55100, 58000],
            14: [23500, 24700, 26000, 27300, 28600, 30000, 31500, 33100, 34800, 36500, 38300, 40200, 42300, 44400, 46600, 48900, 51300, 53900, 56800],
            15: [22800, 24000, 25200, 26400, 27800, 29100, 30600, 32100, 33700, 35400, 37200, 39000, 41000, 43000, 45200, 47400, 49800, 52300, 55200],
            16: [21900, 23000, 24200, 25400, 26700, 28000, 29400, 30900, 32400, 34000, 35700, 37500, 39400, 41300, 43400, 45600, 47900, 50200, 52900],
            17: [21400, 22500, 23600, 24800, 26100, 27400, 28700, 30200, 31700, 33200, 34900, 36700, 38500, 40400, 42400, 44500, 46700, 49100, 51900],
            18: [21000, 22100, 23200, 24400, 25600, 26900, 28200, 29600, 31100, 32600, 34300, 36000, 37800, 39600, 41600, 43700, 45900, 48200, 50900],
            19: [20500, 21600, 22700, 23800, 25000, 26200, 27500, 28900, 30300, 31900, 33400, 35100, 36900, 38700, 40600, 42700, 44800, 47000, 49600],
            20: [20000, 21000, 22100, 23200, 24400, 25600, 26900, 28200, 29600, 31100, 32600, 34300, 36000, 37800, 39600, 41600, 43700, 45900, 48400]
        };

        // ৩. হেল্পার ফাংশন: ইংরেজি সংখ্যাকে বাংলায় রূপান্তর
        function en2bn(num) {
            return num.toLocaleString('en-IN').replace(/\d/g, d => '০১২৩৪৫৬৭৮৯'[d]);
        }

        // ৪. কুইক গ্রেড বাটন সিলেক্ট করা
        function selectQuickGrade(grade, btn) {
            document.getElementById('grade').value = grade;
            document.querySelectorAll('.quick-grade-btn').forEach(b => {
                b.classList.remove('bg-emerald-600', 'text-white');
                b.classList.add('bg-slate-100', 'text-slate-700', 'dark:bg-slate-800', 'dark:text-slate-300');
            });
            btn.classList.remove('bg-slate-100', 'text-slate-700', 'dark:bg-slate-800', 'dark:text-slate-300');
            btn.classList.add('bg-emerald-600', 'text-white');
            onGradeChange();
        }

        // ৫. গ্রেড সিলেক্ট করলে পুরনো ধাপগুলো লোড করা
        function onGradeChange() {
            const grade = document.getElementById('grade').value;
            const basicSelect = document.getElementById('oldBasicPay');

            basicSelect.innerHTML = '<option value="" disabled selected>আপনার বর্তমান মূল বেতন সিলেক্ট করুন</option>';
            if (grade && payScales2015[grade]) {
                payScales2015[grade].forEach((amount, index) => {
                    basicSelect.innerHTML += `<option value="${amount}">ধাপ ${index + 1}: ৳ ${en2bn(amount)}</option>`;
                });
                basicSelect.disabled = false;
            } else {
                basicSelect.disabled = true;
            }

            // যখনই গ্রেড চেঞ্জ হবে, ক্যালকুলেশন প্রিভিউ জিরো করে দেওয়া
            document.getElementById('newBasicPayVal').value = '';
            document.getElementById('previewOldBasic').innerText = '৳০';
            document.getElementById('previewNewBasic').innerText = '৳০';
            document.getElementById('previewDiff').innerText = '৳০';
            document.getElementById('scaleRatioBadge').classList.add('hidden');
            document.getElementById('calculationStepsTable').innerHTML = '<div class="text-[11px] text-slate-500 text-center py-2">বর্তমান মূল বেতন সিলেক্ট করলে হিসাব দেখা যাবে।</div>';
        }

        // ৬. নতুন বেসিক পে হিসাব, ইনক্রিমেন্ট যোগ ও প্রিভিউ আপডেট (অঙ্কসহ)
        function calculateNewBasic() {
            const grade = parseInt(document.getElementById('grade').value);
            const oldBasic = parseInt(document.getElementById('oldBasicPay').value);

            if (!grade || !oldBasic) return;

            let initialOld = payScales2015[grade][0];
            let initialNew = payScales2026[grade][0];
            let newScaleSteps = payScales2026[grade];

            let difference = oldBasic - initialOld;
            let targetNewBasic = initialNew + difference;

            let mappedBasic = initialNew;
            let mappedIndex = 0;

            // ধাপ নির্ধারণ
            if (oldBasic === initialOld) {
                mappedBasic = initialNew;
                mappedIndex = 0;
            } else {
                for (let i = 0; i < newScaleSteps.length; i++) {
                    if (newScaleSteps[i] >= targetNewBasic) {
                        mappedBasic = newScaleSteps[i];
                        mappedIndex = i;
                        break;
                    }
                }
            }

            // ১ জুলাই ২০২৬ এর ইনক্রিমেন্ট যোগ করা (যদি স্কেলে পরের ধাপ থাকে)
            let finalNewBasic = mappedBasic;
            if (mappedIndex + 1 < newScaleSteps.length) {
                finalNewBasic = newScaleSteps[mappedIndex + 1];
            }

            let diff = finalNewBasic - oldBasic;
            let percentage = Math.round((diff / oldBasic) * 100);

            // UI আপডেট
            document.getElementById('previewOldBasic').innerText = '৳' + en2bn(oldBasic);
            document.getElementById('previewNewBasic').innerText = '৳' + en2bn(finalNewBasic);
            document.getElementById('previewDiff').innerText = '+ ৳' + en2bn(diff);

            const badge = document.getElementById('scaleRatioBadge');
            badge.innerText = `${en2bn(percentage)}% বৃদ্ধি`;
            badge.classList.remove('hidden');

            document.getElementById('newBasicPayVal').value = finalNewBasic;

            // অঙ্কের মাধ্যমে টেবিল জেনারেট করা
            let isExactMatch = newScaleSteps.includes(targetNewBasic);
            let mappedText = isExactMatch ? "সমান ধাপ অনুযায়ী" : "সমান ধাপ নেই, পরবর্তী উচ্চতর ধাপ";

            let calcHTML = `
                <div class="overflow-x-auto mt-2">
                    <table class="w-full text-left text-[11px] sm:text-xs border-collapse">
                        <tbody>
                            <tr class="border-b border-emerald-100 dark:border-emerald-800/50">
                                <td class="py-1.5 font-medium text-slate-600 dark:text-slate-400">বর্তমান বেতন - পুরাতন প্রারম্ভিক ধাপ</td>
                                <td class="py-1.5 text-right font-bold text-slate-800 dark:text-slate-200">${en2bn(oldBasic)} - ${en2bn(initialOld)} = ${en2bn(difference)}</td>
                            </tr>
                            <tr class="border-b border-emerald-100 dark:border-emerald-800/50">
                                <td class="py-1.5 font-medium text-slate-600 dark:text-slate-400">নতুন প্রারম্ভিক ধাপ + পার্থক্য</td>
                                <td class="py-1.5 text-right font-bold text-slate-800 dark:text-slate-200">${en2bn(initialNew)} + ${en2bn(difference)} = ${en2bn(targetNewBasic)}</td>
                            </tr>
                            <tr class="border-b border-emerald-100 dark:border-emerald-800/50">
                                <td class="py-1.5 font-bold text-slate-700 dark:text-slate-300">${mappedText}</td>
                                <td class="py-1.5 text-right font-bold text-slate-800 dark:text-slate-200">৳ ${en2bn(mappedBasic)}</td>
                            </tr>
                            <tr class="border-b-2 border-emerald-200 dark:border-emerald-700/60 bg-emerald-50/50 dark:bg-emerald-900/10">
                                <td class="py-2 font-bold text-emerald-700 dark:text-emerald-300 px-1">+ ১ জুলাই ২০২৬ এর বার্ষিক বেতনবৃদ্ধি</td>
                                <td class="py-2 text-right font-black text-emerald-700 dark:text-emerald-300 px-1">৳ ${en2bn(finalNewBasic)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>`;

            document.getElementById('calculationStepsTable').innerHTML = calcHTML;
        }

        // ৭. ২০১৫ সালের বাড়িভাড়া নির্ণয় (শতকরা হার সহ)
        function getOldHouseRentInfo(basic, area) {
            let percent = 0, minAmount = 0;
            if (area === 'dhaka') {
                if(basic <= 9700) { percent = 0.65; minAmount = 5000; }
                else if(basic <= 16000) { percent = 0.60; minAmount = 6400; }
                else if(basic <= 35500) { percent = 0.55; minAmount = 9600; }
                else { percent = 0.50; minAmount = 19500; }
            } else if (area === 'other_city') {
                if(basic <= 9700) { percent = 0.60; minAmount = 4500; }
                else if(basic <= 16000) { percent = 0.55; minAmount = 5800; }
                else if(basic <= 35500) { percent = 0.50; minAmount = 8800; }
                else { percent = 0.45; minAmount = 17800; }
            } else {
                if(basic <= 9700) { percent = 0.55; minAmount = 4000; }
                else if(basic <= 16000) { percent = 0.50; minAmount = 5300; }
                else if(basic <= 35500) { percent = 0.45; minAmount = 8000; }
                else { percent = 0.40; minAmount = 16000; }
            }
            return {
                amount: Math.max(Math.round(basic * percent), minAmount),
                percent: Math.round(percent * 100)
            };
        }

        // ৮. ২০২৮ সালের প্রস্তাবিত বাড়িভাড়া নির্ণয় (শতকরা হার সহ)
        function getNewHouseRentInfo(grade, basic, area) {
            let percent = 0;
            if (grade >= 16 && grade <= 20) {
                percent = (area === 'dhaka') ? 0.60 : (area === 'other_city' ? 0.50 : 0.45);
            } else if (grade >= 10 && grade <= 15) {
                percent = (area === 'dhaka') ? 0.50 : (area === 'other_city' ? 0.40 : 0.35);
            } else if (grade >= 5 && grade <= 9) {
                percent = (area === 'dhaka') ? 0.45 : (area === 'other_city' ? 0.35 : 0.30);
            } else {
                percent = (area === 'dhaka') ? 0.40 : (area === 'other_city' ? 0.30 : 0.25);
            }
            return {
                amount: Math.round(basic * percent),
                percent: Math.round(percent * 100)
            };
        }

        // ৯. চূড়ান্ত ক্যালকুলেশন (সাবমিট ইভেন্ট)
        function calculateSalaryPhases() {
            const grade = parseInt(document.getElementById('grade').value);
            const oldBasic = parseInt(document.getElementById('oldBasicPay').value);
            const newBasic = parseInt(document.getElementById('newBasicPayVal').value);

            if (!grade || !oldBasic || !newBasic) {
                alert('অনুগ্রহ করে গ্রেড এবং বর্তমান মূল বেতন নির্বাচন করুন।');
                return;
            }

            const area = document.getElementById('area').value;
            const children = parseInt(document.getElementById('children').value);
            const age = document.getElementById('age').value;
            const cityTransport = document.getElementById('cityTransport').checked;
            const tiffinProvided = document.getElementById('tiffinProvided').checked;

            const diff = newBasic - oldBasic;
            document.getElementById('resOldBasic').innerText = '৳ ' + en2bn(oldBasic);
            document.getElementById('resNewBasic').innerText = '৳ ' + en2bn(newBasic);
            document.getElementById('resDiff').innerText = '+ ৳ ' + en2bn(diff);
            document.getElementById('sideOldBasic').innerText = '৳ ' + en2bn(oldBasic);
            document.getElementById('sideNewBasic').innerText = '৳ ' + en2bn(newBasic);
            document.getElementById('sideDiff').innerText = '৳ ' + en2bn(diff);

            let p1Percent = (grade >= 1 && grade <= 9) ? 0.40 : 0.50;
            let p2Percent = (grade >= 1 && grade <= 9) ? 0.70 : 0.75;

            let p1Basic = Math.round(oldBasic + (diff * p1Percent));
            let p2Basic = Math.round(oldBasic + (diff * p2Percent));
            let p3Basic = newBasic;
            let p4Basic = newBasic;

            // --- ২০১৫ সালের ভাতাসমূহ (Phase 1, 2, 3 এর জন্য) ---
            let oldHRInfo = getOldHouseRentInfo(oldBasic, area);
            let oldHR = oldHRInfo.amount;
            let oldHRPercent = oldHRInfo.percent;

            let oldMedical = 1500;
            let education = children * 500;
            let oldTiffin = (grade >= 11 && grade <= 20 && !tiffinProvided) ? 200 : 0;
            let oldTransport = (grade >= 11 && grade <= 20 && cityTransport) ? 300 : 0;

            let oldTotalAllowances = oldHR + oldMedical + education + oldTiffin + oldTransport;

            // --- ২০২৮ সালের নতুন ভাতাসমূহ (Phase 4 এর জন্য) ---
            let newHRInfo = getNewHouseRentInfo(grade, newBasic, area);
            let newHR = newHRInfo.amount;
            let newHRPercent = newHRInfo.percent;

            let newMedical = (age === 'under50') ? 3000 : 4000;
            let newTiffin = (grade >= 11 && grade <= 20 && !tiffinProvided) ? 500 : 0;
            let newTransport = (grade >= 11 && grade <= 20 && cityTransport) ? 600 : 0;
            let mobile = (grade <= 5) ? 500 : 150;

            let newTotalAllowances = newHR + newMedical + education + newTiffin + newTransport + mobile;

            // ডায়নামিক HTML তৈরি করার Helper Function (যাতে ভাতার লিস্ট সব ধাপে সঠিক দেখায়)
            const buildOldAllowancesHTML = (bgClass, borderClass) => `
                <div class="text-[11px] space-y-1 mt-2 p-2.5 ${bgClass} dark:bg-slate-800/60 rounded-xl border ${borderClass} dark:border-slate-700/60">
                    <div class="text-slate-500 dark:text-slate-400 mb-1 border-b border-slate-200 dark:border-slate-700 pb-1 font-bold">ভাতাসমূহ (২০১৫ হারে):</div>
                    <div class="flex justify-between"><span>বাড়িভাড়া (${en2bn(oldHRPercent)}%):</span><span class="font-bold">৳ ${en2bn(oldHR)}</span></div>
                    <div class="flex justify-between"><span>চিকিৎসা:</span><span class="font-bold">৳ ${en2bn(oldMedical)}</span></div>
                    ${education > 0 ? `<div class="flex justify-between"><span>শিক্ষা ভাতা:</span><span class="font-bold">৳ ${en2bn(education)}</span></div>` : ''}
                    ${oldTiffin > 0 ? `<div class="flex justify-between"><span>টিফিন ভাতা:</span><span class="font-bold">৳ ${en2bn(oldTiffin)}</span></div>` : ''}
                    ${oldTransport > 0 ? `<div class="flex justify-between"><span>যাতায়াত ভাতা:</span><span class="font-bold">৳ ${en2bn(oldTransport)}</span></div>` : ''}
                    <div class="flex justify-between mt-1 pt-1 border-t border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-extrabold"><span>মোট ভাতা:</span><span>৳ ${en2bn(oldTotalAllowances)}</span></div>
                </div>`;

            // Update Phase 1
            document.getElementById('p1Basic').innerText = '৳ ' + en2bn(p1Basic);
            document.getElementById('p1Gross').innerText = '৳ ' + en2bn(p1GrossCalculation(p1Basic, oldTotalAllowances));
            document.getElementById('p1AllowancesList').innerHTML = buildOldAllowancesHTML('bg-blue-50/50', 'border-blue-100/50');

            // Update Phase 2
            document.getElementById('p2Basic').innerText = '৳ ' + en2bn(p2Basic);
            document.getElementById('p2Gross').innerText = '৳ ' + en2bn(p2GrossCalculation(p2Basic, oldTotalAllowances));
            document.getElementById('p2AllowancesList').innerHTML = buildOldAllowancesHTML('bg-purple-50/50', 'border-purple-100/50');

            // Update Phase 3
            document.getElementById('p3Basic').innerText = '৳ ' + en2bn(p3Basic);
            document.getElementById('p3Gross').innerText = '৳ ' + en2bn(p3GrossCalculation(p3Basic, oldTotalAllowances));
            document.getElementById('p3AllowancesList').innerHTML = buildOldAllowancesHTML('bg-teal-50/50', 'border-teal-100/50');

            // Update Phase 4
            document.getElementById('p4Basic').innerText = '৳ ' + en2bn(p4Basic);
            document.getElementById('p4Gross').innerText = '৳ ' + en2bn(p4GrossCalculation(p4Basic, newTotalAllowances));

            let p4AllowancesHTML = `
                <div class="text-[11px] space-y-1 mt-2 p-2.5 bg-emerald-50/70 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800/60">
                    <div class="text-emerald-800 dark:text-emerald-400 mb-1 border-b border-emerald-200 dark:border-emerald-800/60 pb-1 font-bold">ভাতাসমূহ (২০২৮ নতুন হারে):</div>
                    <div class="flex justify-between"><span>নতুন বাড়িভাড়া (${en2bn(newHRPercent)}%):</span><span class="font-bold">৳ ${en2bn(newHR)}</span></div>
                    <div class="flex justify-between"><span>নতুন চিকিৎসা:</span><span class="font-bold">৳ ${en2bn(newMedical)}</span></div>
                    ${education > 0 ? `<div class="flex justify-between"><span>শিক্ষা ভাতা:</span><span class="font-bold">৳ ${en2bn(education)}</span></div>` : ''}
                    ${newTiffin > 0 ? `<div class="flex justify-between"><span>টিফিন ভাতা:</span><span class="font-bold">৳ ${en2bn(newTiffin)}</span></div>` : ''}
                    ${newTransport > 0 ? `<div class="flex justify-between"><span>যাতায়াত ভাতা:</span><span class="font-bold">৳ ${en2bn(newTransport)}</span></div>` : ''}
                    <div class="flex justify-between"><span>মোবাইল ভাতা:</span><span class="font-bold">৳ ${en2bn(mobile)}</span></div>
                    <div class="flex justify-between mt-1 pt-1 border-t border-emerald-300 dark:border-emerald-700/60 text-emerald-900 dark:text-emerald-200 font-extrabold"><span>নতুন মোট ভাতা:</span><span>৳ ${en2bn(newTotalAllowances)}</span></div>
                </div>`;
            document.getElementById('p4AllowancesList').innerHTML = p4AllowancesHTML;

            // --- বকেয়া ও বার্ষিক ভাতা হিসাব ---
            let basicIncrease = p1Basic - oldBasic;

            // নতুন নিয়ম অনুযায়ী বিশেষ সুবিধা হিসাব
            let specialBenefitPercent = (grade >= 1 && grade <= 9) ? 0.10 : 0.15;
            let specialBenefit = Math.max(1500, Math.round(oldBasic * specialBenefitPercent));

            let monthlyArrear = basicIncrease - specialBenefit;
            let julAugArrear = monthlyArrear * 2;

            let festivalOld = oldBasic;
            let noboborshoOld = Math.round(oldBasic * 0.20);

            let festivalNew = newBasic;
            let noboborshoNew = Math.round(newBasic * 0.15);

            let arrearsHTML = `
                    <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">মাসিক বকেয়া (প্রথম ধাপ)</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">বর্ধিত মূল বেতন ৳${en2bn(basicIncrease)} - বিশেষ সুবিধা ৳${en2bn(specialBenefit)}</div>
                        </div>
                        <div class="font-extrabold text-slate-900 dark:text-white text-right">৳${en2bn(monthlyArrear)}</div>
                    </div>
                    <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-3 pt-3">
                        <div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">জুলাই ও আগস্ট ২০২৬ এর বকেয়া</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">আদেশ জারির তারিখ (১৭ সেপ্টেম্বর) পর্যন্ত বকেয়া প্রাপ্য</div>
                        </div>
                        <div class="font-extrabold text-slate-900 dark:text-white text-right">৳${en2bn(julAugArrear)}</div>
                    </div>

                    <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-3 pt-1">
                        <div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">উৎসব ভাতা, ২০২৭ পর্যন্ত</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">বছরে ২টি, প্রতিটি ৩০ জুন ২০২৬ এর মূল বেতনের সমান</div>
                        </div>
                        <div class="font-extrabold text-slate-900 dark:text-white text-right">৳${en2bn(festivalOld)} × ২</div>
                    </div>
                    <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-3 pt-3">
                        <div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">বাংলা নববর্ষ ভাতা, ২০২৭ পর্যন্ত</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">পূর্বের ২০% হারে</div>
                        </div>
                        <div class="font-extrabold text-slate-900 dark:text-white text-right">৳${en2bn(noboborshoOld)}</div>
                    </div>
                    <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-3 pt-3">
                        <div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">উৎসব ভাতা, ২০২৮ থেকে</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">বছরে ২টি, তখনকার মূল বেতনের সমান</div>
                        </div>
                        <div class="font-extrabold text-slate-900 dark:text-white text-right">৳${en2bn(festivalNew)} × ২</div>
                    </div>
                    <div class="flex justify-between items-start pt-3">
                        <div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">বাংলা নববর্ষ ভাতা, ২০২৮ থেকে</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">মূল বেতনের ১৫%</div>
                        </div>
                        <div class="font-extrabold text-slate-900 dark:text-white text-right">৳${en2bn(noboborshoNew)}</div>
                    </div>
                `;
            document.getElementById('arrearsSection').innerHTML = arrearsHTML;

            // Show Results Area
            const resultSection = document.getElementById('result');
            resultSection.classList.remove('hidden');
            resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function p1GrossCalculation(basic, allowances) { return basic + allowances; }
        function p2GrossCalculation(basic, allowances) { return basic + allowances; }
        function p3GrossCalculation(basic, allowances) { return basic + allowances; }
        function p4GrossCalculation(basic, allowances) { return basic + allowances; }

        // ১০. কপি এবং রিসেট ফাংশন
        function copySummary() {
            const text = `আমার ৯ম পে-স্কেল হিসাব:\nপূর্বের মূল বেতন: ${document.getElementById('resOldBasic').innerText}\nনতুন মূল বেতন: ${document.getElementById('resNewBasic').innerText}\nচূড়ান্ত সর্বমোট বেতন: ${document.getElementById('p4Gross').innerText}`;
            navigator.clipboard.writeText(text).then(() => {
                document.getElementById('copyBtnText').innerText = 'কপি হয়েছে!';
                setTimeout(() => document.getElementById('copyBtnText').innerText = 'সারাংশ কপি', 2000);
            });
        }

        function shareCopyLink(btn) {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const textSpan = btn.querySelector('.share-copy-text');
                if(textSpan) {
                    textSpan.innerText = 'কপি হয়েছে!';
                    setTimeout(() => textSpan.innerText = 'লিংক কপি', 2000);
                }
            });
        }

        function resetCalculator() {
            document.getElementById('salaryForm').reset();
            document.querySelectorAll('.quick-grade-btn').forEach(b => {
                b.classList.remove('bg-emerald-600', 'text-white');
                b.classList.add('bg-slate-100', 'text-slate-700', 'dark:bg-slate-800', 'dark:text-slate-300');
            });
            document.getElementById('oldBasicPay').innerHTML = '<option value="" disabled selected>👈 প্রথমে ওপরের গ্রেড নির্বাচন করুন</option>';
            document.getElementById('oldBasicPay').disabled = true;
            document.getElementById('newBasicPayVal').value = '';
            document.getElementById('previewOldBasic').innerText = '৳০';
            document.getElementById('previewNewBasic').innerText = '৳০';
            document.getElementById('previewDiff').innerText = '৳০';
            document.getElementById('scaleRatioBadge').classList.add('hidden');
            document.getElementById('calculationStepsTable').innerHTML = '<div class="text-[11px] text-slate-500 text-center py-2">বর্তমান মূল বেতন সিলেক্ট করলে হিসাব দেখা যাবে।</div>';
            document.getElementById('result').classList.add('hidden');
        }
    </script>

@endpush
