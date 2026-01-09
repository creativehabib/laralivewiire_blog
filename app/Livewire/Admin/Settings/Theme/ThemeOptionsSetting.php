<?php

namespace App\Livewire\Admin\Settings\Theme;

use App\Models\Admin\Page;
use App\Models\Category;
use Livewire\Component;

class ThemeOptionsSetting extends Component
{
    public array $general = [];
    public array $header = [];
    public array $layout = [];
    public array $homepage = [];
    public array $post = [];
    public array $ads = [];
    public array $seo = [];
    public array $footer = [];
    public array $social_links = [];
    public string $activeMenu = 'general';
    public string $primary_font = '';
    public string $primary_font_weights = '';
    public string $body_font_size = '';
    public array $google_fonts = [];
    public array $categories = [];
    public array $pages = [];
    public array $timezoneOptions = [];
    public array $categoryColors = [];
    public array $homepageSectionOrder = [];
    public array $homepageSectionPostCounts = [];

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
        $this->body_font_size = (string) setting('body_font_size', '16px');
        $this->google_fonts = $this->loadGoogleFonts();
        $this->timezoneOptions = \DateTimeZone::listIdentifiers();
        $this->categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
            ])
            ->all();
        $this->pages = Page::query()
            ->orderBy('title')
            ->get(['id', 'title'])
            ->map(fn (Page $page) => [
                'id' => $page->id,
                'title' => $page->title,
            ])
            ->all();
        $this->general = [
            'site_title' => (string) setting('site_title', ''),
            'site_tagline' => (string) setting('site_tagline', ''),
            'site_logo_light' => (string) setting('site_logo_light', ''),
            'site_logo_dark' => (string) setting('site_logo_dark', ''),
            'site_favicon' => (string) setting('site_favicon', ''),
            'timezone' => (string) setting('timezone', config('app.timezone', 'Asia/Dhaka')),
            'date_display_format' => (string) setting('date_display_format', 'gregorian_and_bangla'),
            'contact_address' => (string) setting('contact_address', ''),
            'site_email' => (string) setting('site_email', ''),
            'site_phone' => (string) setting('site_phone', ''),
        ];
        $this->header = [
            'sticky_header' => filter_var(setting('sticky_header', true), FILTER_VALIDATE_BOOLEAN),
            'breaking_news_enabled' => filter_var(setting('breaking_news_enabled', true), FILTER_VALIDATE_BOOLEAN),
            'breaking_news_category_id' => setting('breaking_news_category_id'),
            'breaking_news_speed' => (int) setting('breaking_news_speed', 60),
            'search_toggle' => filter_var(setting('search_toggle', true), FILTER_VALIDATE_BOOLEAN),
        ];
        $this->layout = [
            'primary_theme_color' => (string) setting('primary_theme_color', '#2563eb'),
            'dark_mode_enabled' => filter_var(setting('dark_mode_enabled', true), FILTER_VALIDATE_BOOLEAN),
        ];
        $storedCategoryColors = setting('category_colors', []);
        foreach ($this->categories as $category) {
            $categoryId = $category['id'];
            $this->categoryColors[$categoryId] = $storedCategoryColors[$categoryId] ?? '#94a3b8';
        }
        $this->homepage = [
            'featured_slider_enabled' => filter_var(setting('featured_slider_enabled', true), FILTER_VALIDATE_BOOLEAN),
            'featured_slider_category_id' => setting('featured_slider_category_id'),
        ];
        $storedSectionOrder = setting('homepage_section_order', []);
        $storedSectionCounts = setting('homepage_section_post_counts', []);
        foreach ($this->categories as $category) {
            $categoryId = $category['id'];
            $this->homepageSectionOrder[$categoryId] = $storedSectionOrder[$categoryId] ?? null;
            $this->homepageSectionPostCounts[$categoryId] = $storedSectionCounts[$categoryId] ?? 6;
        }
        $this->post = [
            'share_facebook' => filter_var(setting('share_facebook', true), FILTER_VALIDATE_BOOLEAN),
            'share_whatsapp' => filter_var(setting('share_whatsapp', true), FILTER_VALIDATE_BOOLEAN),
            'share_telegram' => filter_var(setting('share_telegram', true), FILTER_VALIDATE_BOOLEAN),
            'author_box_enabled' => filter_var(setting('author_box_enabled', true), FILTER_VALIDATE_BOOLEAN),
            'related_news_count' => (int) setting('related_news_count', 4),
            'comments_system' => (string) setting('comments_system', 'livewire'),
        ];
        $this->ads = [
            'header_ad_code' => (string) setting('header_ad_code', ''),
            'sidebar_ad_code' => (string) setting('sidebar_ad_code', ''),
            'in_article_ad_code' => (string) setting('in_article_ad_code', ''),
            'in_article_ad_paragraph' => (int) setting('in_article_ad_paragraph', 3),
        ];
        $this->seo = [
            'meta_description' => (string) setting('meta_description', ''),
            'facebook_url' => (string) setting('facebook_url', ''),
            'youtube_url' => (string) setting('youtube_url', ''),
            'x_url' => (string) setting('x_url', ''),
            'instagram_url' => (string) setting('instagram_url', ''),
            'google_analytics_code' => (string) setting('google_analytics_code', ''),
            'facebook_pixel_code' => (string) setting('facebook_pixel_code', ''),
        ];
        $footerLinks = setting('footer_useful_links', []);
        $this->footer = [
            'copyright_text' => (string) setting('footer_copyright_text', ''),
            'about_summary' => (string) setting('footer_about_summary', ''),
            'useful_links' => is_array($footerLinks) ? $footerLinks : [],
        ];

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

    public function saveGeneral(): void
    {
        set_setting('site_title', trim((string) ($this->general['site_title'] ?? '')), 'general');
        set_setting('site_tagline', trim((string) ($this->general['site_tagline'] ?? '')), 'theme-options');
        set_setting('site_logo_light', $this->general['site_logo_light'] ?? '', 'theme-options');
        set_setting('site_logo_dark', $this->general['site_logo_dark'] ?? '', 'theme-options');
        set_setting('site_favicon', $this->general['site_favicon'] ?? '', 'general');
        set_setting('timezone', $this->general['timezone'] ?? config('app.timezone', 'Asia/Dhaka'), 'general');
        set_setting('date_display_format', $this->general['date_display_format'] ?? 'gregorian_and_bangla', 'general');
        set_setting('contact_address', trim((string) ($this->general['contact_address'] ?? '')), 'theme-options');
        set_setting('site_email', trim((string) ($this->general['site_email'] ?? '')), 'general');
        set_setting('site_phone', trim((string) ($this->general['site_phone'] ?? '')), 'general');

        session()->flash('success', 'General settings updated successfully!');
    }

    public function saveHeader(): void
    {
        set_setting('sticky_header', filter_var($this->header['sticky_header'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');
        set_setting('breaking_news_enabled', filter_var($this->header['breaking_news_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');
        set_setting('breaking_news_category_id', $this->header['breaking_news_category_id'] ?? null, 'theme-options');
        set_setting('breaking_news_speed', (int) ($this->header['breaking_news_speed'] ?? 60), 'theme-options');
        set_setting('search_toggle', filter_var($this->header['search_toggle'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');

        session()->flash('success', 'Header settings updated successfully!');
    }

    public function saveLayout(): void
    {
        set_setting('primary_theme_color', $this->layout['primary_theme_color'] ?? '#2563eb', 'theme-options');
        set_setting('dark_mode_enabled', filter_var($this->layout['dark_mode_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');
        set_setting('category_colors', $this->categoryColors, 'theme-options');
        set_setting('primary_font', trim($this->primary_font), 'theme-options');
        set_setting('primary_font_weights', trim($this->primary_font_weights), 'theme-options');
        set_setting('body_font_size', trim($this->body_font_size), 'theme-options');

        session()->flash('success', 'Layout settings updated successfully!');
    }

    public function saveHomepage(): void
    {
        set_setting('featured_slider_enabled', filter_var($this->homepage['featured_slider_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');
        set_setting('featured_slider_category_id', $this->homepage['featured_slider_category_id'] ?? null, 'theme-options');
        set_setting('homepage_section_order', $this->homepageSectionOrder, 'theme-options');
        set_setting('homepage_section_post_counts', $this->homepageSectionPostCounts, 'theme-options');

        session()->flash('success', 'Homepage settings updated successfully!');
    }

    public function savePost(): void
    {
        set_setting('share_facebook', filter_var($this->post['share_facebook'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');
        set_setting('share_whatsapp', filter_var($this->post['share_whatsapp'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');
        set_setting('share_telegram', filter_var($this->post['share_telegram'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');
        set_setting('author_box_enabled', filter_var($this->post['author_box_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN), 'theme-options');
        set_setting('related_news_count', (int) ($this->post['related_news_count'] ?? 4), 'theme-options');
        set_setting('comments_system', $this->post['comments_system'] ?? 'livewire', 'theme-options');

        session()->flash('success', 'Post settings updated successfully!');
    }

    public function saveAds(): void
    {
        set_setting('header_ad_code', $this->ads['header_ad_code'] ?? '', 'theme-options');
        set_setting('sidebar_ad_code', $this->ads['sidebar_ad_code'] ?? '', 'theme-options');
        set_setting('in_article_ad_code', $this->ads['in_article_ad_code'] ?? '', 'theme-options');
        set_setting('in_article_ad_paragraph', (int) ($this->ads['in_article_ad_paragraph'] ?? 3), 'theme-options');

        session()->flash('success', 'Ad settings updated successfully!');
    }

    public function saveSeo(): void
    {
        set_setting('meta_description', $this->seo['meta_description'] ?? '', 'theme-options');
        set_setting('facebook_url', $this->seo['facebook_url'] ?? '', 'theme-options');
        set_setting('youtube_url', $this->seo['youtube_url'] ?? '', 'theme-options');
        set_setting('x_url', $this->seo['x_url'] ?? '', 'theme-options');
        set_setting('instagram_url', $this->seo['instagram_url'] ?? '', 'theme-options');
        set_setting('google_analytics_code', $this->seo['google_analytics_code'] ?? '', 'theme-options');
        set_setting('facebook_pixel_code', $this->seo['facebook_pixel_code'] ?? '', 'theme-options');
        set_setting('social_links', $this->formatSocialLinksForStorage($this->social_links), 'theme-options');

        session()->flash('success', 'SEO settings updated successfully!');
    }

    public function saveFooter(): void
    {
        set_setting('footer_copyright_text', $this->footer['copyright_text'] ?? '', 'theme-options');
        set_setting('footer_about_summary', $this->footer['about_summary'] ?? '', 'theme-options');
        set_setting('footer_useful_links', $this->footer['useful_links'] ?? [], 'theme-options');

        session()->flash('success', 'Footer settings updated successfully!');
    }

    public function saveTypography(): void
    {
        set_setting('primary_font', trim($this->primary_font), 'theme-options');
        set_setting('primary_font_weights', trim($this->primary_font_weights), 'theme-options');
        set_setting('body_font_size', trim($this->body_font_size), 'theme-options');
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
            'categories' => $this->categories,
            'pages' => $this->pages,
            'timezoneOptions' => $this->timezoneOptions,
        ])->layout('components.layouts.app', [
            'title' => 'Theme Options',
        ]);
    }
}
