<?php

use App\Support\PermalinkManager;

it('falls back to default when custom permalink is missing post identifier', function () {
    [$structure, $custom] = PermalinkManager::validatedStructure(
        PermalinkManager::STRUCTURE_CUSTOM,
        '%year%/%monthnum%'
    );

    expect($structure)->toBe(PermalinkManager::DEFAULT_STRUCTURE)
        ->and($custom)->toBeNull();
});

it('rejects custom permalink templates with unknown placeholders', function () {
    [$structure, $custom] = PermalinkManager::validatedStructure(
        PermalinkManager::STRUCTURE_CUSTOM,
        'blog/%postname%/%unknown%'
    );

    expect($structure)->toBe(PermalinkManager::DEFAULT_STRUCTURE)
        ->and($custom)->toBeNull();
});

it('sanitizes custom permalinks while keeping them valid', function () {
    [$structure, $custom] = PermalinkManager::validatedStructure(
        PermalinkManager::STRUCTURE_CUSTOM,
        'https://example.com/blog/%postname%/'
    );

    expect($structure)->toBe(PermalinkManager::STRUCTURE_CUSTOM)
        ->and($custom)->toBe('blog/%postname%');
});

it('ensures templateFor falls back to default on invalid custom structure', function () {
    $template = PermalinkManager::templateFor(
        PermalinkManager::STRUCTURE_CUSTOM,
        '%year%/%monthnum%'
    );

    expect($template)->toBe('%postname%');
});
