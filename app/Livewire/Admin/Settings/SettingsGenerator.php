<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\Slug;
use App\Support\PermalinkManager;
use App\Support\SlugHelper;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Illuminate\Support\Str;

class SettingsGenerator extends Component
{
    public string $group = 'general';
    public array $data = [];

    public function mount(string $group = 'general'): void
    {
        $this->group = $group;

        $config = $this->groupConfig();
        abort_if(! $config, 404);

        foreach (($config['fields'] ?? []) as $field) {
            $key     = $field['key'];
            $default = $field['default'] ?? null;

            $value = setting($key, $default);

            // IMPORTANT: switch হলে boolean normalize
            if (($field['type'] ?? null) === 'switch') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            $this->data[$key] = $value;
        }
    }

    protected function groupConfig(): ?array
    {
        return config("settings.groups.{$this->group}");
    }

    protected function rules(): array
    {
        $config = $this->groupConfig();
        $rules = [];

        foreach (($config['fields'] ?? []) as $field) {
            $rules["data.{$field['key']}"] = $field['rules'] ?? ['nullable'];
        }

        return $rules;
    }

    public function save(): void
    {
        $this->validate();
        $this->validatePermalinkRoutingConflicts();

        $config = $this->groupConfig();
        $previous = $this->capturePrefixSettings($config);

        foreach (($config['fields'] ?? []) as $field) {
            $key = $field['key'];
            $value = $this->data[$key] ?? null;

            // switch checkbox normalizes (string/1/0 সব handle)
            if (($field['type'] ?? null) === 'switch') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            if (in_array($key, ['category_slug_prefix', 'tag_slug_prefix', 'page_slug_prefix'], true)) {
                $value = is_string($value) ? trim($value) : $value;
                $value = $value === '' ? null : $value;
            }

            if (in_array($key, ['post_url_extension', 'category_url_extension', 'tag_url_extension', 'page_url_extension'], true)) {
                $value = PermalinkManager::normalizeExtensionInput(is_string($value) ? trim($value) : $value);
            }

            if (in_array($key, ['homepage_page_id', 'posts_page_id'], true)) {
                if ($value === '') {
                    $value = null;
                }
                $value = $value === null ? null : (int) $value;
            }

            set_setting($key, $value, $this->group);
        }

        $this->syncSlugPrefixes($previous);
        $this->dispatch('media-toast', type: 'success', message: "{$config['title']} settings saved.");
    }
    public function resetToDefaults(): void
    {
        $config = $this->groupConfig();
        abort_if(! $config, 404);

        $previous = $this->capturePrefixSettings($config);

        foreach (($config['fields'] ?? []) as $field) {
            $key = $field['key'] ?? null;
            $type = $field['type'] ?? null;

            if (! $key || $type === 'permalink_preview') {
                continue;
            }

            $default = $field['default'] ?? null;

            if ($type === 'switch') {
                $default = filter_var($default, FILTER_VALIDATE_BOOLEAN);
            }

            $this->data[$key] = $default;
            set_setting($key, $default, $this->group);
        }

        $this->syncSlugPrefixes($previous);
        $this->dispatch('media-toast', type: 'success', message: "{$config['title']} settings reset to defaults.");
    }
    public function getPagePreviewProperty(): string
    {
        return \App\Support\PermalinkManager::pagePreview('sample-page');
    }
    public function render()
    {
        return view('livewire.admin.settings.generator', [
            'config' => $this->groupConfig(),
            'groups' => config('settings.groups'),
        ])->layout('components.layouts.app', [
            'title' => 'Settings - ' . (config("settings.groups.{$this->group}.title") ?? 'Settings'),
        ]);
    }

    protected function capturePrefixSettings(?array $config): array
    {
        if (! $config) {
            return [];
        }

        $keys = [
            'permalink_structure',
            'custom_permalink_structure',
            'category_slug_prefix',
            'tag_slug_prefix',
            'page_slug_prefix',
        ];

        $availableKeys = collect($config['fields'] ?? [])
            ->pluck('key')
            ->intersect($keys)
            ->all();

        return collect($availableKeys)
            ->mapWithKeys(fn ($key) => [$key => setting($key)])
            ->all();
    }

    protected function syncSlugPrefixes(array $previous): void
    {
        if ($this->group !== 'permalinks') {
            return;
        }

        $current = [
            'permalink_structure' => setting('permalink_structure'),
            'custom_permalink_structure' => setting('custom_permalink_structure'),
            'category_slug_prefix' => setting('category_slug_prefix'),
            'tag_slug_prefix' => setting('tag_slug_prefix'),
            'page_slug_prefix' => setting('page_slug_prefix'),
        ];

        $changed = false;
        foreach ($current as $key => $value) {
            if (($previous[$key] ?? null) !== $value) {
                $changed = true;
                break;
            }
        }

        if (! $changed) {
            return;
        }

        if (
            ($previous['category_slug_prefix'] ?? null) !== ($current['category_slug_prefix'] ?? null)
            || ($previous['tag_slug_prefix'] ?? null) !== ($current['tag_slug_prefix'] ?? null)
            || ($previous['page_slug_prefix'] ?? null) !== ($current['page_slug_prefix'] ?? null)
        ) {
            Slug::where('reference_type', Category::class)
                ->update(['prefix' => SlugHelper::prefixForModel(new Category())]);
            Slug::where('reference_type', Tag::class)
                ->update(['prefix' => SlugHelper::prefixForModel(new Tag())]);
            Slug::where('reference_type', Page::class)
                ->update(['prefix' => SlugHelper::prefixForModel(new Page())]);
        }

        if (
            ($previous['permalink_structure'] ?? null) !== ($current['permalink_structure'] ?? null)
            || ($previous['custom_permalink_structure'] ?? null) !== ($current['custom_permalink_structure'] ?? null)
        ) {
            $this->syncPostSlugs();
        }
    }

    protected function syncPostSlugs(): void
    {
        [$structure, $custom] = PermalinkManager::currentStructure();
        $template = PermalinkManager::normalizedTemplate($structure, $custom);
        $usePostId = Str::contains($template, '%post_id%');

        Post::query()
            ->select('id', 'name')
            ->chunkById(200, function ($posts) use ($usePostId): void {
                foreach ($posts as $post) {
                    $post->syncSlug($usePostId ? (string) $post->getKey() : null);
                }
            });
    }

    protected function validatePermalinkRoutingConflicts(): void
    {
        if ($this->group !== 'permalinks') {
            return;
        }

        $prefixes = [
            'category' => trim((string) ($this->data['category_slug_prefix'] ?? '')),
            'tag' => trim((string) ($this->data['tag_slug_prefix'] ?? '')),
            'page' => trim((string) ($this->data['page_slug_prefix'] ?? '')),
        ];

        $extensions = [
            'category' => PermalinkManager::normalizeExtensionInput($this->data['category_url_extension'] ?? null) ?? '',
            'tag' => PermalinkManager::normalizeExtensionInput($this->data['tag_url_extension'] ?? null) ?? '',
            'page' => PermalinkManager::normalizeExtensionInput($this->data['page_url_extension'] ?? null) ?? '',
            'post' => PermalinkManager::normalizeExtensionInput($this->data['post_url_extension'] ?? null) ?? '',
        ];

        $slugOnlyTypes = collect($prefixes)
            ->filter(fn ($prefix) => $prefix === '')
            ->keys()
            ->values();

        $errors = [];

        foreach ($slugOnlyTypes as $type) {
            $conflicts = $slugOnlyTypes
                ->reject(fn ($otherType) => $otherType === $type)
                ->filter(fn ($otherType) => ($extensions[$otherType] ?? '') === ($extensions[$type] ?? ''))
                ->values()
                ->all();

            if ($conflicts !== []) {
                $list = collect($conflicts)->map(fn ($value) => ucfirst($value))->implode(', ');
                $errors["data.{$type}_slug_prefix"] = ucfirst($type) . " prefix ফাঁকা রাখতে চাইলে {$list} এর সাথে একই extension রাখা যাবে না।";
            }
        }

        $postTemplate = PermalinkManager::normalizedTemplate(
            $this->data['permalink_structure'] ?? null,
            $this->data['custom_permalink_structure'] ?? null,
        );

        if ($postTemplate === '%postname%') {
            foreach ($slugOnlyTypes as $type) {
                if (($extensions[$type] ?? '') === ($extensions['post'] ?? '')) {
                    $errors["data.{$type}_slug_prefix"] = ucfirst($type) . ' prefix ফাঁকা এবং Post permalink `%postname%` হলে একই extension ব্যবহার করা যাবে না।';
                }
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }
}
