<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Livewire\Component;

class PostComments extends Component
{
    public Post $post;

    public Collection $comments;

    public string $name = '';

    public string $email = '';

    public ?string $website = null;

    public string $content = '';

    public ?string $successMessage = null;

    public ?int $parentId = null;

    public ?string $replyingTo = null;

    public array $commentSettings = [];

    public bool $allowComments = true;

    public ?string $blockedReason = null;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->commentSettings = $this->loadCommentSettings();
        $this->applyCommentVisibilityRules();
        $this->prefillUserDetails();
        $this->loadComments();
    }

    protected function rules(): array
    {
        return [
            'name'    => [$this->commentSettings['require_name_email'] ? 'required' : 'nullable', 'string', 'max:255'],
            'email'   => [$this->commentSettings['require_name_email'] ? 'required' : 'nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'content' => ['required', 'string', 'min:5'],
            'parentId' => ['nullable', 'integer', 'exists:comments,id'],
        ];
    }

    public function submit(): void
    {
        abort_unless($this->allowComments, 403);

        if ($this->commentSettings['require_login'] && ! Auth::check()) {
            abort(403, 'শুধুমাত্র লগইন করা ব্যবহারকারীরা মন্তব্য করতে পারবেন।');
        }

        $validated = $this->validate();

        $parentId = null;

        if (! empty($validated['parentId'])) {
            $parentId = $this->post
                ->comments()
                ->approved()
                ->findOrFail($validated['parentId'])
                ->id;
        }

        $status = $this->determineInitialStatus($validated['email'] ?? null);
        $status = $this->applyModerationRules($status, $validated);

        $this->post->comments()->create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'website'        => $validated['website'] ?? null,
            'content'        => $validated['content'],
            'status'         => $status,
            'user_id'        => Auth::id(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
            'parent_id'      => $parentId,
        ]);

        $this->content = '';
        $this->successMessage = $status === 'approved'
            ? 'আপনার মন্তব্যটি প্রকাশিত হয়েছে।'
            : 'আপনার মন্তব্যটি রিভিউর জন্য পাঠানো হয়েছে।';
        $this->parentId = null;
        $this->replyingTo = null;
        $this->loadComments();
    }

    public function setReply(int $commentId): void
    {
        $comment = $this->post->comments()->approved()->findOrFail($commentId);

        $this->parentId = $comment->id;
        $this->replyingTo = $comment->name;
        $this->successMessage = null;

        $this->dispatch('scrollToCommentForm');
    }

    public function cancelReply(): void
    {
        $this->parentId = null;
        $this->replyingTo = null;
    }

    public function render()
    {
        return view('livewire.frontend.post-comments', [
            'allowComments' => $this->allowComments,
            'blockedReason' => $this->blockedReason,
            'threadDepth'   => $this->commentSettings['thread_depth'] ?? 0,
            'threaded'      => $this->commentSettings['threaded_comments'] ?? false,
        ]);
    }

    protected function loadComments(): void
    {
        $order = ($this->commentSettings['comments_order'] ?? 'older') === 'newer' ? 'desc' : 'asc';

        $this->comments = $this->post
            ->comments()
            ->whereNull('parent_id')
            ->approved()
            ->orderBy('created_at', $order)
            ->with(['parent', 'repliesRecursive', 'user'])
            ->get();
    }

    protected function prefillUserDetails(): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();
        $this->name = $user?->name ?? '';
        $this->email = $user?->email ?? '';
    }

    protected function loadCommentSettings(): array
    {
        return [
            'allow_new_posts'        => (bool) setting('comment_allow_new_posts', true),
            'require_name_email'     => (bool) setting('comment_require_name_email', true),
            'require_login'          => (bool) setting('comment_require_login', false),
            'auto_close'             => (bool) setting('comment_auto_close', false),
            'auto_close_days'        => (int) setting('comment_auto_close_days', 14),
            'threaded_comments'      => (bool) setting('comment_threaded', true),
            'thread_depth'           => (int) setting('comment_thread_depth', 5),
            'manual_approval'        => (bool) setting('comment_manual_approval', true),
            'require_prior_approval' => (bool) setting('comment_require_prior_approval', false),
            'comments_order'         => (string) setting('comment_order', 'older'),
            'moderation_links'       => (int) setting('comment_moderation_links', 2),
            'moderation_keys'        => (string) setting('comment_moderation_keys', ''),
            'disallowed_keys'        => (string) setting('comment_disallowed_keys', ''),
        ];
    }

    protected function applyCommentVisibilityRules(): void
    {
        $this->allowComments = (bool) $this->post->allow_comments;

        if ($this->commentSettings['require_login'] && ! Auth::check()) {
            $this->allowComments = false;
            $this->blockedReason = 'শুধুমাত্র লগইন করা ব্যবহারকারীরা মন্তব্য করতে পারবেন।';
        }

        if (! $this->allowComments) {
            return;
        }

        if ($this->commentSettings['auto_close'] && $this->post->created_at) {
            $ageInDays = $this->post->created_at->diffInDays(now());

            if ($ageInDays >= $this->commentSettings['auto_close_days']) {
                $this->allowComments = false;
                $this->blockedReason = 'এই পোস্টে মন্তব্য করার সময়সীমা শেষ হয়েছে।';
            }
        }
    }

    protected function determineInitialStatus(?string $email): string
    {
        if ($this->commentSettings['manual_approval']) {
            return 'pending';
        }

        if ($this->commentSettings['require_prior_approval'] && $email) {
            $hasApproved = $this->post
                ->comments()
                ->approved()
                ->where('email', $email)
                ->exists();

            if (! $hasApproved) {
                return 'pending';
            }
        }

        return 'approved';
    }

    protected function applyModerationRules(string $currentStatus, array $commentData): string
    {
        $contentToScan = Str::of(
            implode("\n", array_filter([
                $commentData['content'] ?? '',
                $commentData['name'] ?? '',
                $commentData['email'] ?? '',
                $commentData['website'] ?? '',
                Request::ip(),
            ]))
        )->lower();

        $disallowedKeys = $this->prepareKeywords($this->commentSettings['disallowed_keys'] ?? '');

        foreach ($disallowedKeys as $keyword) {
            if ($keyword !== '' && Str::of($keyword)->isNotEmpty() && $contentToScan->contains(Str::lower($keyword))) {
                return 'trash';
            }
        }

        $moderationLinks = $this->commentSettings['moderation_links'] ?? 0;

        if ($moderationLinks > 0) {
            preg_match_all('/https?:\/\/[^\s]+/i', $commentData['content'] ?? '', $matches);

            if (count($matches[0]) >= $moderationLinks) {
                return 'pending';
            }
        }

        $moderationKeys = $this->prepareKeywords($this->commentSettings['moderation_keys'] ?? '');

        foreach ($moderationKeys as $keyword) {
            if ($keyword !== '' && Str::of($keyword)->isNotEmpty() && $contentToScan->contains(Str::lower($keyword))) {
                return 'pending';
            }
        }

        return $currentStatus;
    }

    protected function prepareKeywords(string $keywords): array
    {
        return collect(preg_split('/\r\n|\n|\r/', trim($keywords)))
            ->map(fn ($keyword) => trim($keyword))
            ->filter()
            ->values()
            ->all();
    }
}
