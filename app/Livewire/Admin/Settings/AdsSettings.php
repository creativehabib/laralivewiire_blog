<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting; // আপনার Setting মডেল ইমপোর্ট করুন
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdsSettings extends Component
{
    use WithFileUploads;

    public $mode = 'disabled';
    public $auto_ads_code = '';
    public $unit_ads_client_id = '';

    // ads.txt
    public $uploadedFile;

    public function mount()
    {
        // ডাটাবেস থেকে সেটিংস লোড করা
        // আপনার হেল্পার ফাংশন থাকলে settings('key') ব্যবহার করতে পারেন
        $this->mode = Setting::where('key', 'adsense_mode')->value('value') ?? 'disabled';
        $this->auto_ads_code = Setting::where('key', 'adsense_auto_code')->value('value') ?? '';
        $this->unit_ads_client_id = Setting::where('key', 'adsense_unit_client_id')->value('value') ?? '';
    }

    public function updatedUploadedFile()
    {
        $this->validate([
            'uploadedFile' => 'required|file|mimes:txt|max:512', // 512KB max
        ]);

        // ফাইল কন্টেন্ট রিড করা
        $content = File::get($this->uploadedFile->getRealPath());

        // public/ads.txt ফাইলে রাইট করা
        File::put(public_path('ads.txt'), $content);

        $this->uploadedFile = null;
        $this->dispatch('media-toast', type: 'success', message: 'ads.txt file uploaded successfully!');
    }

    public function save()
    {
        // ১. ভ্যালিডেশন রুলস (ডাইনামিক)
        $rules = [
            'mode' => 'required|in:disabled,auto,unit',
        ];

        // মোড অনুযায়ী ভ্যালিডেশন
        if ($this->mode === 'auto') {
            $rules['auto_ads_code'] = 'required|string';
        }

        if ($this->mode === 'unit') {
            $rules['unit_ads_client_id'] = 'required|string';
        }

        $this->validate($rules);

        // ২. ডাটাবেসে সেভ করা
        $settings = [
            'adsense_mode' => $this->mode,
            'adsense_auto_code' => $this->auto_ads_code,
            'adsense_unit_client_id' => $this->unit_ads_client_id,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // ৩. ক্যাশ ক্লিয়ার করা (যদি আপনি ক্যাশ ব্যবহার করেন)
        Cache::forget('general_settings'); // অথবা আপনার ক্যাশ-এর নাম

        // ৪. সাকসেস মেসেজ
        $this->dispatch('media-toast', type: 'success', message: 'AdSense settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.ads-settings')
            ->layout('components.layouts.app', ['title' => 'AdSense Settings']);
    }
}
