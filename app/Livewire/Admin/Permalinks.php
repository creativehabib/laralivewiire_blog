<?php

namespace App\Livewire\Admin;

use App\Models\GeneralSetting;
use App\Support\PermalinkManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Permalinks extends Component
{
    public $permalink_structure = PermalinkManager::DEFAULT_STRUCTURE;
    public $custom_permalink_structure;
    public $category_slug_prefix_enabled = true;

    public $tag_slug_prefix;

    public function mount(Request $request)
    {
        $settings = GeneralSetting::take(1)->first();
        if (! is_null($settings)) {
            $this->permalink_structure = $settings->permalink_structure ?: PermalinkManager::DEFAULT_STRUCTURE;
            $this->custom_permalink_structure = $settings->custom_permalink_structure;
            $this->category_slug_prefix_enabled = $settings->category_slug_prefix_enabled ?? true;
            $this->tag_slug_prefix = $settings->tag_slug_prefix ?? '';
        }

        if ($this->permalink_structure === PermalinkManager::STRUCTURE_CUSTOM && blank($this->custom_permalink_structure)) {
            $this->permalink_structure = PermalinkManager::DEFAULT_STRUCTURE;
        }
        $this->category_slug_prefix_enabled = (bool) $this->category_slug_prefix_enabled;
    }

    public function updatePermalinks(): void
    {
        $availableStructures = array_keys(PermalinkManager::availableStructures());
        $availableStructures[] = PermalinkManager::STRUCTURE_CUSTOM;

        $rules = [
            'permalink_structure' => ['required', Rule::in($availableStructures)],
            'custom_permalink_structure' => ['nullable', 'string'],
            'category_slug_prefix_enabled' => ['boolean'],
            'tag_slug_prefix' => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-z0-9\/_-]*$/'],
        ];

        if ($this->permalink_structure === PermalinkManager::STRUCTURE_CUSTOM) {
            $rules['custom_permalink_structure'][] = function (string $attribute, $value, $fail) {
                $normalized = PermalinkManager::sanitizeCustomStructure($value);

                if ($normalized === '') {
                    $fail('The custom permalink structure cannot be empty.');
                    return;
                }

                $tokens = PermalinkManager::extractTokens($value ?? '');
                $allowed = PermalinkManager::allowedTokens();
                $unknown = collect($tokens)->diff($allowed);

                if ($unknown->isNotEmpty()) {
                    $fail('Unknown placeholder(s): ' . $unknown->implode(', '));
                    return;
                }

                if (in_array('%postname%', $tokens, true) && in_array('%post_id%', $tokens, true)) {
                    $fail('Please use either %postname% or %post_id%, not both together.');
                    return;
                }

                if (! in_array('%postname%', $tokens, true) && ! in_array('%post_id%', $tokens, true)) {
                    $fail('The structure must include %postname% or %post_id% to identify posts.');
                    return;
                }

                if (preg_match('#https?://#i', (string) $value)) {
                    $fail('Please enter only the path portion without the domain.');
                }
            };
        }

        $this->validate($rules, [], [
            'custom_permalink_structure' => 'custom permalink structure',
        ]);

        $structure = $this->permalink_structure;
        $customStructure = null;

        if ($structure === PermalinkManager::STRUCTURE_CUSTOM) {
            $customStructure = PermalinkManager::sanitizeCustomStructure($this->custom_permalink_structure);
            $this->custom_permalink_structure = $customStructure;
        }

        $settings = GeneralSetting::first();

        if (! $settings) {
            $settings = GeneralSetting::create([]);
        }

        $settings->update([
            'permalink_structure' => $structure,
            'custom_permalink_structure' => $customStructure,
            'category_slug_prefix_enabled' => (bool) $this->category_slug_prefix_enabled,
            'tag_slug_prefix'              => trim($this->tag_slug_prefix, '/'),
        ]);

        Cache::forget('general_settings');
        Artisan::call('route:clear');

        $this->dispatch('media-toast', type: 'success', message: 'Permalink settings updated successfully');
    }

    public function getPermalinkPreviewProperty(): string
    {
        $structure = $this->permalink_structure;
        $custom = $structure === PermalinkManager::STRUCTURE_CUSTOM ? $this->custom_permalink_structure : null;

        return PermalinkManager::previewSample($structure, $custom);
    }

    public function getSanitizedTagPrefixProperty(): string
    {
        return trim((string) ($this->tag_slug_prefix ?? 'tag'), '/') ?: 'tag';
    }

    public function getTagPreviewProperty(): string
    {
        $prefix = $this->sanitizedTagPrefix;

        return PermalinkManager::formatUrl($prefix . '/your-tag');
    }

    public function getCategoryPreviewProperty(): string
    {
        $path = $this->category_slug_prefix_enabled
            ? 'category/your-category'
            : 'your-category';

        return PermalinkManager::formatUrl($path);
    }
    public function render()
    {
        return view('livewire.admin.permalinks',[
            'permalinkOptions' => PermalinkManager::availableStructures(),
            'permalinkTokens' => PermalinkManager::allowedTokens(),
        ]);
    }
}
