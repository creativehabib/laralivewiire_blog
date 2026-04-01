# Theme system

এই প্রজেক্টে Botble-style theme workflow এর জন্য base theme manager যোগ করা হয়েছে।

## Theme folder structure

প্রতিটি theme থাকবে:

`resources/views/themes/<theme-slug>/`

Required:
- `theme.json` (with `slug`)

Optional (view override):
- `livewire/frontend/homepage.blade.php`
- `livewire/frontend/single.blade.php`
- এবং `livewire.frontend.*` এর যেকোনো view

## View loading behavior (important)

ডিফল্ট কনফিগে (`config/themes.php`) `fallback_to_core=false`।
তাই rendering order হচ্ছে:

1. Active theme (`resources/views/themes/<active-theme>/...`)
2. Default theme (`resources/views/themes/default/...`)
3. Core fallback (`resources/views/livewire/frontend/...`) **বন্ধ**

এর মানে: `resources/views/livewire/frontend` ফোল্ডার ফাঁকা রাখলেও theme থেকেই view load হবে (active/default theme এ file থাকলে)।

যদি আপনি core fallback চালু করতে চান, তাহলে `config/themes.php` এ:

```php
'fallback_to_core' => true,
```

## Commands

```bash
php artisan theme:list
php artisan theme:activate default
php artisan theme:install /absolute/or/relative/path/to/theme.zip
```

## ZIP package rules

ZIP এর ভিতরে `theme.json` থাকা বাধ্যতামূলক।

Valid examples:
- `my-theme/theme.json`
- `theme.json` (zip root)

## Admin UI

Dashboard এর **Appearance -> Themes** মেনু থেকে theme upload/install, activate/deactivate এবং delete করা যাবে।
