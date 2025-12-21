<?php

namespace App\Livewire\Admin\Settings;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class CommentsSettings extends Component
{
    public bool $notify_linked_blogs = false;
    public bool $allow_pingbacks = false;
    public bool $allow_comments_default = true;

    public bool $require_name_email = true;
    public bool $require_login = false;
    public bool $auto_close = false;
    public int $auto_close_days = 14;
    public bool $cookies_opt_in = false;

    public bool $threaded_comments = true;
    public int $thread_depth = 5;

    public bool $paginate_comments = false;
    public int $comments_per_page = 50;
    public string $comments_page_display = 'last';
    public string $comments_order = 'older';

    public bool $email_notify_any = false;
    public bool $email_notify_moderation = true;

    public bool $manual_approval = true;
    public bool $require_prior_approval = false;

    public int $moderation_links = 2;
    public string $moderation_keys = '';
    public string $disallowed_keys = '';

    public bool $show_avatars = true;
    public string $avatar_rating = 'g';
    public string $avatar_default = 'mystery';

    public function mount(): void
    {
        $this->notify_linked_blogs     = (bool) setting('comment_notify_linked_blogs', false);
        $this->allow_pingbacks         = (bool) setting('comment_allow_pingbacks', false);
        $this->allow_comments_default  = (bool) setting('comment_allow_new_posts', true);

        $this->require_name_email      = (bool) setting('comment_require_name_email', true);
        $this->require_login           = (bool) setting('comment_require_login', false);
        $this->auto_close              = (bool) setting('comment_auto_close', false);
        $this->auto_close_days         = (int)  setting('comment_auto_close_days', 14);
        $this->cookies_opt_in          = (bool) setting('comment_cookies_opt_in', false);

        $this->threaded_comments       = (bool) setting('comment_threaded', true);
        $this->thread_depth            = (int)  setting('comment_thread_depth', 5);

        $this->paginate_comments       = (bool) setting('comment_paginate', false);
        $this->comments_per_page       = (int)  setting('comment_per_page', 50);
        $this->comments_page_display   = (string) setting('comment_page_display', 'last');
        $this->comments_order          = (string) setting('comment_order', 'older');

        $this->email_notify_any        = (bool) setting('comment_email_notify_any', false);
        $this->email_notify_moderation = (bool) setting('comment_email_notify_moderation', true);

        $this->manual_approval         = (bool) setting('comment_manual_approval', true);
        $this->require_prior_approval  = (bool) setting('comment_require_prior_approval', false);

        $this->moderation_links        = (int) setting('comment_moderation_links', 2);
        $this->moderation_keys         = (string) setting('comment_moderation_keys', '');
        $this->disallowed_keys         = (string) setting('comment_disallowed_keys', '');

        $this->show_avatars            = (bool) setting('comment_show_avatars', true);
        $this->avatar_rating           = (string) setting('comment_avatar_rating', 'g');
        $this->avatar_default          = (string) setting('comment_avatar_default', 'mystery');
    }

    public function updatedAutoClose(bool $value): void
    {
        if (! $value) {
            $this->auto_close_days = 14;
        }
    }

    protected function rules(): array
    {
        return [
            'notify_linked_blogs'     => ['boolean'],
            'allow_pingbacks'         => ['boolean'],
            'allow_comments_default'  => ['boolean'],

            'require_name_email'      => ['boolean'],
            'require_login'           => ['boolean'],
            'auto_close'              => ['boolean'],
            'auto_close_days'         => ['integer', 'min:1', 'max:365'],
            'cookies_opt_in'          => ['boolean'],

            'threaded_comments'       => ['boolean'],
            'thread_depth'            => ['integer', 'min:1', 'max:10'],

            'paginate_comments'       => ['boolean'],
            'comments_per_page'       => ['integer', 'min:1', 'max:200'],
            'comments_page_display'   => ['in:first,last'],
            'comments_order'          => ['in:older,newer'],

            'email_notify_any'        => ['boolean'],
            'email_notify_moderation' => ['boolean'],

            'manual_approval'         => ['boolean'],
            'require_prior_approval'  => ['boolean'],

            'moderation_links'        => ['integer', 'min:0', 'max:20'],
            'moderation_keys'         => ['nullable', 'string'],
            'disallowed_keys'         => ['nullable', 'string'],

            'show_avatars'            => ['boolean'],
            'avatar_rating'           => ['in:g,pg,r,x'],
            'avatar_default'          => ['in:mystery,blank,gravatar,identicon,wavatar,monsterid,retro'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $settings = [
            'comment_notify_linked_blogs'     => $this->notify_linked_blogs,
            'comment_allow_pingbacks'         => $this->allow_pingbacks,
            'comment_allow_new_posts'         => $this->allow_comments_default,

            'comment_require_name_email'      => $this->require_name_email,
            'comment_require_login'           => $this->require_login,
            'comment_auto_close'              => $this->auto_close,
            'comment_auto_close_days'         => $this->auto_close_days,
            'comment_cookies_opt_in'          => $this->cookies_opt_in,

            'comment_threaded'                => $this->threaded_comments,
            'comment_thread_depth'            => $this->thread_depth,

            'comment_paginate'                => $this->paginate_comments,
            'comment_per_page'                => $this->comments_per_page,
            'comment_page_display'            => $this->comments_page_display,
            'comment_order'                   => $this->comments_order,

            'comment_email_notify_any'        => $this->email_notify_any,
            'comment_email_notify_moderation' => $this->email_notify_moderation,

            'comment_manual_approval'         => $this->manual_approval,
            'comment_require_prior_approval'  => $this->require_prior_approval,

            'comment_moderation_links'        => $this->moderation_links,
            'comment_moderation_keys'         => $this->moderation_keys,
            'comment_disallowed_keys'         => $this->disallowed_keys,

            'comment_show_avatars'            => $this->show_avatars,
            'comment_avatar_rating'           => $this->avatar_rating,
            'comment_avatar_default'          => $this->avatar_default,
        ];

        foreach ($settings as $key => $value) {
            set_setting($key, $value, 'comments');
        }

        Cache::forget('general_settings');

        $this->dispatch('media-toast', type: 'success', message: 'Comment settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.comments-settings')
            ->layout('components.layouts.app', [
                'title' => 'Comment Settings',
            ]);
    }
}
