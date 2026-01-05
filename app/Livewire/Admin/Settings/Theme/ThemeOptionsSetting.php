<?php

namespace App\Livewire\Admin\Settings\Theme;

use Livewire\Component;

class ThemeOptionsSetting extends Component
{
    public $social_links = [];

    public function mount()
    {
        // পেজ লোড হওয়ার সময় ডিফল্ট একটি খালি অপশন রাখতে পারেন
        $this->addSocialLink();
    }

    // নতুন সোশ্যাল লিঙ্ক অপশন যোগ করার ফাংশন
    public function addSocialLink()
    {
        $this->social_links[] = [
            'name' => '',
            'icon' => '',
            'url'  => '',
            'color' => '#000000',
            'bg_color' => '#ffffff'
        ];
    }

    // কোনো অপশন মুছে ফেলার ফাংশন (প্রয়োজন হলে)
    public function removeSocialLink($index)
    {
        unset($this->social_links[$index]);
        $this->social_links = array_values($this->social_links); // ইন্ডেক্স ঠিক করার জন্য
    }

    public function save()
    {
        // এখানে ডাটাবেসে সেভ করার লজিক লিখুন
        // উদাহরণ: Setting::updateOrCreate(['key' => 'social_links'], ['value' => json_encode($this->social_links)]);
        session()->flash('success', 'Social links updated successfully!');
    }
    public function render()
    {
        return view('livewire.admin.settings.theme.theme-options-setting')->layout('components.layouts.app', [
            'title' => 'Theme Options',
        ]);
    }
}
