<?php

namespace App\Livewire\Admin\Settings;

use App\Models\ApiToken;
use Illuminate\Support\Collection;
use Livewire\Component;

class ApiTokensSettings extends Component
{
    public string $token_name = 'dashboard-token';

    public ?int $expires_in_days = 30;

    public ?string $plain_text_token = null;

    public Collection $tokens;

    public function mount(): void
    {
        $this->loadTokens();
    }

    public function generateToken(): void
    {
        $validated = $this->validate([
            'token_name' => ['required', 'string', 'max:100'],
            'expires_in_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        $expiresAt = $validated['expires_in_days']
            ? now()->addDays((int) $validated['expires_in_days'])
            : null;

        $tokenPayload = auth()->user()->createApiToken(
            $validated['token_name'],
            $expiresAt,
        );

        $this->plain_text_token = $tokenPayload['plain_text_token'];
        $this->token_name = 'dashboard-token';
        $this->loadTokens();

        $this->dispatch('media-toast', type: 'success', message: 'New API token created successfully.');
    }

    public function revokeToken(int $tokenId): void
    {
        ApiToken::query()
            ->where('id', $tokenId)
            ->where('user_id', auth()->id())
            ->delete();

        $this->loadTokens();

        $this->dispatch('media-toast', type: 'success', message: 'API token revoked successfully.');
    }

    private function loadTokens(): void
    {
        $this->tokens = auth()->user()
            ->apiTokens()
            ->latest('id')
            ->get(['id', 'name', 'last_used_at', 'expires_at', 'created_at']);
    }

    public function render()
    {
        return view('livewire.admin.settings.api-tokens-settings')->layout('components.layouts.app', [
            'title' => __('API / Developer'),
        ]);
    }
}
