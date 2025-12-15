<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

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

        $config = $this->groupConfig();

        foreach (($config['fields'] ?? []) as $field) {
            $key = $field['key'];
            $value = $this->data[$key] ?? null;

            // switch checkbox normalizes (string/1/0 সব handle)
            if (($field['type'] ?? null) === 'switch') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            set_setting($key, $value, $this->group);
        }

        $this->dispatch('media-toast', type: 'success', message: "{$config['title']} settings saved.");
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
}
