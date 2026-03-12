<?php

namespace App\Livewire\Admin\Settings;

use App\Models\ApiToken;
use Livewire\Component;
use Livewire\WithPagination;

class ApiTokensSettings extends Component
{
    use WithPagination;

    public string $token_name = 'dashboard-token';

    public ?int $expires_in_days = 30;

    public ?string $plain_text_token = null;

    /**
     * @var array<int, string>
     */
    public array $selected_scopes = ['content:read'];

    public ?int $editingTokenId = null;

    public string $editingTokenName = '';

    /**
     * @return array<string, string>
     */
    public function availableScopes(): array
    {
        return [
            'content:read' => 'Read content (posts, pages, categories, tags)',
            'content:write' => 'Create/update content',
            'comments:moderate' => 'Moderate comments',
            'media:upload' => 'Upload media',
            'settings:read' => 'Read app settings',
        ];
    }

    public function generateToken(): void
    {
        $scopeKeys = array_keys($this->availableScopes());

        $validated = $this->validate([
            'token_name' => ['required', 'string', 'max:100'],
            'expires_in_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'selected_scopes' => ['required', 'array', 'min:1'],
            'selected_scopes.*' => ['string', 'in:'.implode(',', $scopeKeys)],
        ]);

        $expiresAt = $validated['expires_in_days']
            ? now()->addDays((int) $validated['expires_in_days'])
            : null;

        $tokenPayload = auth()->user()->createApiToken(
            $validated['token_name'],
            $expiresAt,
            $validated['selected_scopes'],
        );

        $this->plain_text_token = $tokenPayload['plain_text_token'];
        $this->token_name = 'dashboard-token';
        $this->selected_scopes = ['content:read'];
        $this->resetPage();

        $this->dispatch('media-toast', type: 'success', message: 'New API token created successfully.');
    }

    public function startEditingToken(int $tokenId): void
    {
        $token = ApiToken::query()
            ->where('id', $tokenId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $token) {
            return;
        }

        $this->editingTokenId = $token->id;
        $this->editingTokenName = $token->name;
    }

    public function cancelEditingToken(): void
    {
        $this->editingTokenId = null;
        $this->editingTokenName = '';
    }

    public function saveTokenName(): void
    {
        $validated = $this->validate([
            'editingTokenName' => ['required', 'string', 'max:100'],
        ]);

        if (! $this->editingTokenId) {
            return;
        }

        ApiToken::query()
            ->where('id', $this->editingTokenId)
            ->where('user_id', auth()->id())
            ->update([
                'name' => $validated['editingTokenName'],
            ]);

        $this->cancelEditingToken();
        $this->dispatch('media-toast', type: 'success', message: 'API token name updated successfully.');
    }

    public function revokeToken(int $tokenId): void
    {
        ApiToken::query()
            ->where('id', $tokenId)
            ->where('user_id', auth()->id())
            ->delete();

        $this->dispatch('media-toast', type: 'success', message: 'API token revoked successfully.');
    }

    public function render()
    {
        $tokens = auth()->user()
            ->apiTokens()
            ->withCount([
                'requestLogs as calls_last_24h' => fn ($q) => $q->where('created_at', '>=', now()->subDay()),
            ])
            ->latest('id')
            ->paginate(10, ['id', 'name', 'abilities', 'last_used_ip', 'last_used_at', 'expires_at', 'created_at']);

        return view('livewire.admin.settings.api-tokens-settings', [
            'tokens' => $tokens,
            'scopes' => $this->availableScopes(),
        ])->layout('components.layouts.app', [
            'title' => __('API / Developer'),
        ]);
    }
}
