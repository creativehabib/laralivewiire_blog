<?php

use App\Livewire\Admin\Settings\SettingsGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('blocks permalink save when multiple slug-only archive types share same extension', function () {
    $component = new SettingsGenerator();
    $component->group = 'permalinks';
    $component->data = [
        'permalink_structure' => 'day_and_name',
        'custom_permalink_structure' => '',
        'post_url_extension' => '',
        'category_slug_prefix' => '',
        'tag_slug_prefix' => '',
        'page_slug_prefix' => 'page',
        'category_url_extension' => '',
        'tag_url_extension' => '',
        'page_url_extension' => '',
    ];

    $method = new ReflectionMethod(SettingsGenerator::class, 'validatePermalinkRoutingConflicts');
    $method->setAccessible(true);

    try {
        $method->invoke($component);
        $this->fail('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())->toHaveKey('data.category_slug_prefix');
        expect($exception->errors())->toHaveKey('data.tag_slug_prefix');
    }
});

it('blocks slug-only page routes when post permalink is postname with same extension', function () {
    $component = new SettingsGenerator();
    $component->group = 'permalinks';
    $component->data = [
        'permalink_structure' => 'post_name',
        'custom_permalink_structure' => '',
        'post_url_extension' => '.html',
        'category_slug_prefix' => 'category',
        'tag_slug_prefix' => 'tag',
        'page_slug_prefix' => '',
        'category_url_extension' => '',
        'tag_url_extension' => '',
        'page_url_extension' => '.html',
    ];

    $method = new ReflectionMethod(SettingsGenerator::class, 'validatePermalinkRoutingConflicts');
    $method->setAccessible(true);

    try {
        $method->invoke($component);
        $this->fail('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())->toHaveKey('data.page_slug_prefix');
    }
});

it('allows slug-only permalink setup when extensions are unique', function () {
    $component = new SettingsGenerator();
    $component->group = 'permalinks';
    $component->data = [
        'permalink_structure' => 'post_name',
        'custom_permalink_structure' => '',
        'post_url_extension' => '.html',
        'category_slug_prefix' => '',
        'tag_slug_prefix' => '',
        'page_slug_prefix' => '',
        'category_url_extension' => '.cat',
        'tag_url_extension' => '.tag',
        'page_url_extension' => '.page',
    ];

    $method = new ReflectionMethod(SettingsGenerator::class, 'validatePermalinkRoutingConflicts');
    $method->setAccessible(true);

    $method->invoke($component);

    expect(true)->toBeTrue();
});
