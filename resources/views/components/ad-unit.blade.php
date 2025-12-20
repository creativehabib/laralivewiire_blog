@props(['slotId', 'format' => 'auto', 'responsive' => 'true'])

{{-- শুধু যদি মোড 'unit' হয় এবং Client ID থাকে তখনই অ্যাড রেন্ডার হবে --}}
@if(setting('adsense_mode') === 'unit' && !empty(setting('adsense_unit_client_id')))
    <div class="my-4 ad-container flex justify-center overflow-hidden">
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ setting('adsense_unit_client_id') }}"
             data-ad-slot="{{ $slotId }}"
             data-ad-format="{{ $format }}"
             data-full-width-responsive="{{ $responsive }}"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@endif
