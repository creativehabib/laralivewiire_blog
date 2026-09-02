<?php

use App\Http\Middleware\CheckIfInstalled;
use App\Support\ThemeManager;

it('provides the salary calculator view for every bundled theme', function (string $theme) {
    config()->set('themes.default', $theme);

    expect(ThemeManager::resolveView('livewire.salary-calculator.salary-calculator'))
        ->toBe("themes.{$theme}.livewire.salary-calculator.salary-calculator");
})->with(['default', 'premium-blog']);

it('renders salary calculator seo metadata in the frontend layout', function () {
    $this->withoutMiddleware(CheckIfInstalled::class)
        ->get(route('tools.salary-calculator'))
        ->assertOk()
        ->assertSee('সরকারি বেতন ক্যালকুলেটর ২০২৬')
        ->assertSee('বাংলাদেশের সরকারি চাকরিজীবীদের জন্য ২০১৫ পে স্কেলের গ্রেড', escape: false)
        ->assertSee(route('tools.salary-calculator'), escape: false);
});
