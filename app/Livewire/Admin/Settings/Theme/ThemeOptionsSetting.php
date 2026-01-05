<?php

namespace App\Livewire\Admin\Settings\Theme;

use Livewire\Component;

class ThemeOptionsSetting extends Component
{
    public array $social_links = [];
    public string $activeMenu = 'general';
    public string $primary_font = '';
    public string $primary_font_weights = '';
    public array $google_fonts = [];

    public function mount()
    {
        $menus = config('theme-options.menus', []);
        $defaultMenu = $menus[0]['id'] ?? 'general';
        $requestedMenu = request()->query('as', $defaultMenu);
        $menuIds = collect($menus)->pluck('id')->all();

        $this->activeMenu = in_array($requestedMenu, $menuIds, true) ? $requestedMenu : $defaultMenu;
        $this->social_links = $this->normalizeSocialLinks(setting('social_links', []));
        $this->primary_font = (string) setting('primary_font', 'Hind Siliguri');
        $this->primary_font_weights = (string) setting('primary_font_weights', '300;400;500;600;700');
        $this->google_fonts = $this->loadGoogleFonts();

        // পেজ লোড হওয়ার সময় ডিফল্ট একটি খালি অপশন রাখতে পারেন
        if (empty($this->social_links)) {
            $this->addSocialLink();
        }
    }

    // নতুন সোশ্যাল লিঙ্ক অপশন যোগ করার ফাংশন
    public function addSocialLink(): void
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
    public function removeSocialLink($index): void
    {
        unset($this->social_links[$index]);
        $this->social_links = array_values($this->social_links); // ইন্ডেক্স ঠিক করার জন্য
    }

    public function save(): void
    {
        set_setting('social_links', $this->formatSocialLinksForStorage($this->social_links), 'theme-options');
        session()->flash('success', 'Social links updated successfully!');
    }

    public function saveTypography(): void
    {
        set_setting('primary_font', trim($this->primary_font), 'theme-options');
        set_setting('primary_font_weights', trim($this->primary_font_weights), 'theme-options');
        session()->flash('success', 'Typography settings updated successfully!');
    }

    public function updatedPrimaryFont(string $value): void
    {
        $fontFamily = trim($value);
        $matchedFont = collect($this->google_fonts)->firstWhere('family', $fontFamily);

        if (! $matchedFont) {
            return;
        }

        $variants = $matchedFont['variants'] ?? [];
        $weights = collect($variants)
            ->filter(fn ($variant) => is_numeric($variant))
            ->values()
            ->all();

        if ($weights) {
            $this->primary_font_weights = implode(';', $weights);
        }
    }

    protected function loadGoogleFonts(): array
    {
        $path = base_path('resources/data/google-fonts.json');

        if (! file_exists($path)) {
            return [];
        }

        $fonts = json_decode(file_get_contents($path), true);

        if (! is_array($fonts)) {
            return [];
        }

        $first = $fonts[0] ?? null;

        if (is_string($first)) {
            return array_map(fn (string $font) => [
                'family' => $font,
                'variants' => [],
            ], $fonts);
        }

        return $fonts;
    }

    protected function formatSocialLinksForStorage(array $socialLinks): array
    {
        return array_map(function (array $link) {
            return [
                ['key' => 'name', 'value' => $link['name'] ?? ''],
                ['key' => 'icon', 'value' => $link['icon'] ?? ''],
                ['key' => 'url', 'value' => $link['url'] ?? ''],
                ['key' => 'color', 'value' => $link['color'] ?? '#000000'],
                ['key' => 'background-color', 'value' => $link['bg_color'] ?? '#ffffff'],
            ];
        }, $socialLinks);
    }

    protected function normalizeSocialLinks($socialLinks): array
    {
        if (! is_array($socialLinks)) {
            return [];
        }

        $normalized = [];

        foreach ($socialLinks as $link) {
            if (! is_array($link)) {
                continue;
            }

            if (array_key_exists('name', $link)) {
                $normalized[] = [
                    'name' => $link['name'] ?? '',
                    'icon' => $link['icon'] ?? '',
                    'url' => $link['url'] ?? '',
                    'color' => $link['color'] ?? '#000000',
                    'bg_color' => $link['bg_color'] ?? '#ffffff',
                ];
                continue;
            }

            $mapped = [
                'name' => '',
                'icon' => '',
                'url' => '',
                'color' => '#000000',
                'bg_color' => '#ffffff',
            ];

            foreach ($link as $pair) {
                if (! is_array($pair) || ! array_key_exists('key', $pair)) {
                    continue;
                }

                $key = $pair['key'];
                $value = $pair['value'] ?? null;

                if ($key === 'background-color') {
                    $mapped['bg_color'] = $value ?? $mapped['bg_color'];
                    continue;
                }

                if (array_key_exists($key, $mapped)) {
                    $mapped[$key] = $value;
                }
            }

            $normalized[] = $mapped;
        }

        return $normalized;
    }
    public function render()
    {
        return view('livewire.admin.settings.theme.theme-options-setting', [
            'activeMenu' => $this->activeMenu,
            'menus' => config('theme-options.menus', []),
            'googleFonts' => $this->google_fonts,
        ])->layout('components.layouts.app', [
            'title' => 'Theme Options',
        ]);
    }
}
