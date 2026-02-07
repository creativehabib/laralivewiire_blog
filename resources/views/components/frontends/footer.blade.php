<footer class="bg-secondary text-white mt-8 {{ setting('breaking_news_position', 'top') === 'bottom' ? 'mb-16' : '' }}">
    @php
        $footerMenu = get_menu_by_location('footer');
        $footerMenuItems = $footerMenu?->items ?? collect();
    @endphp
    <div class="container px-4 py-8 grid md:grid-cols-4 gap-6 text-sm">
        <div>
            <h3 class="font-semibold mb-2">বাংলা নিউজ পোর্টাল</h3>
            <p class="text-xs text-slate-100/85">
                নির্ভুল, নিরপেক্ষ ও বস্তুনিষ্ঠ সংবাদ পরিবেশনে আমরা প্রতিশ্রুতিবদ্ধ।
            </p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">দ্রুত লিংক</h3>
            @if($footerMenuItems->isNotEmpty())
                <x-frontends.menu-list :items="$footerMenuItems" variant="footer" />
            @else
                <ul class="space-y-1 text-xs text-slate-100/85">
                    <li><a href="#" class="hover:underline">আমাদের সম্পর্কে</a></li>
                    <li><a href="#" class="hover:underline">যোগাযোগ</a></li>
                    <li><a href="#" class="hover:underline">এডভার্টাইজমেন্ট</a></li>
                </ul>
            @endif
        </div>
        <div>
            <h3 class="font-semibold mb-2">ক্যাটাগরি</h3>
            <ul class="space-y-1 text-xs text-slate-100/85">
                <li><a href="#" class="hover:underline">জাতীয়</a></li>
                <li><a href="#" class="hover:underline">আন্তর্জাতিক</a></li>
                <li><a href="#" class="hover:underline">খেলা</a></li>
                <li><a href="#" class="hover:underline">বিনোদন</a></li>
            </ul>
        </div>
        <div>
            <h3 class="font-semibold mb-2">যোগাযোগ</h3>
            <p class="text-xs text-slate-100/85">
                ঢাকা, বাংলাদেশ<br />
                ফোন: +8801XXXXXXXXX<br />
                ইমেইল: info@example.com
            </p>
        </div>
    </div>
    <div class="bg-secondary-light text-xs text-center py-2 text-slate-100">
        © ২০২৫ বাংলা নিউজ পোর্টাল. সর্বস্বত্ব সংরক্ষিত।
    </div>
</footer>
