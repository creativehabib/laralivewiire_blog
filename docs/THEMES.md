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

> Theme view পাওয়া গেলে সেটি ব্যবহার হবে, না থাকলে আগের ডিফল্ট `resources/views/livewire/frontend/*` fallback হবে।

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
