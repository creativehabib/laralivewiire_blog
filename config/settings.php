<?php

use App\Support\PermalinkManager;

return [
    'groups' => [
        'general' => [
            'title' => 'General',
            'permission' => 'setting.view',
            'fields' => [
                [
                    'key' => 'site_title',
                    'label' => 'Site title',
                    'type' => 'text',
                    'default' => 'My Website',
                    'rules' => ['required','string','max:120'],
                    'hint' => 'Website name shown in title and header.',
                ],
                [
                    'key' => 'site_description',
                    'label' => 'Site description',
                    'type' => 'textarea',
                    'default' => '',
                    'rules' => ['nullable','string','max:255'],
                    'hint' => 'Site description shown in meta description.',
                ],
                [
                    'key' => 'site_keywords',
                    'label' => 'Site keywords',
                    'type' => 'textarea',
                    'default' => '',
                    'rules' => ['nullable','string','max:255'],
                    'hint' => 'Site keywords shown in keywords and description. example: keyword1, keyword2, keyword3, keyword4, keyword5, keyword6',
                ],
                [
                    'key' => 'site_email',
                    'label' => 'Site email',
                    'type' => 'email',
                    'default' => 'admin@example.com',
                    'rules' => ['nullable','email','max:120'],
                ],
                [
                    'key' => 'site_phone',
                    'label' => 'Site phone number',
                    'type' => 'text',
                    'default' => '',
                    'rules' => ['nullable','numeric','digits_between:10,15'],
                ],
                [
                    'key' => 'logo',
                    'label' => 'Logo',
                    'type' => 'image',
                    'default' => null,
                    'rules' => ['nullable','string','max:255'],
                ],
                [
                    'key' => 'site_favicon',
                    'label' => 'Favicon',
                    'type' => 'image',
                    'default' => null,
                    'rules' => ['nullable','string','max:255'],
                ],
                [
                    'key' => 'timezone',
                    'label' => 'Timezone',
                    'type' => 'select',
                    'default' => env('APP_TIMEZONE', 'Asia/Dhaka'),
                    'options' => array_combine(DateTimeZone::listIdentifiers(), DateTimeZone::listIdentifiers()),
                    'rules' => ['required','string'],
                ],
                [
                    'key' => 'date_display_format',
                    'label' => 'Top bar date format',
                    'type' => 'select',
                    'default' => 'gregorian_and_bangla',
                    'options' => [
                        'gregorian_and_bangla' => 'বাংলা ও গ্রেগরিয়ান',
                        'gregorian_only' => 'শুধু গ্রেগরিয়ান',
                    ],
                    'rules' => ['required','in:gregorian_only,gregorian_and_bangla'],
                    'hint' => 'টপ বারে তারিখ কোন ফরমেট এ দেখাবে তা নির্ধারণ করুন।',
                ],
                [
                    'key' => 'site_copyright',
                    'label' => 'Site copyright',
                    'type' => 'textarea',
                    'default' => '',
                    'rules' => ['nullable','string'],
                ],
                [
                    'key' => 'maintenance_mode',
                    'label' => 'Maintenance mode',
                    'type' => 'switch',
                    'default' => false,
                    'rules' => ['boolean'],
                    'hint' => 'This site is currently in maintenance mode.',
                ],
            ],
        ],

        'permalinks' => [
            'title' => 'Permalinks',
            'fields' => [

                [
                    'key' => 'permalink_structure',
                    'type' => 'permalink_structure',
                    'label' => 'Permalink structure',
                    'default' => PermalinkManager::DEFAULT_STRUCTURE,
                    'rules' => ['required', 'string'],
                    'hint' => 'SEO friendly URL structure choose করুন।',
                ],

                [
                    'key' => 'custom_permalink_structure',
                    'type' => 'text',
                    'label' => 'Custom structure',
                    'default' => '',
                    'rules' => ['nullable', 'string', 'max:190'],
                    'hint' => 'Example: news/%year%/%postname%',
                    'visible_when' => [
                        'permalink_structure' => 'custom',
                    ],
                ],

                [
                    'key' => 'category_slug_prefix',
                    'type' => 'text',
                    'label' => 'Category URL base',
                    'default' => 'category',
                    'rules' => ['nullable', 'string', 'max:50'],
                    'hint' => 'Example: category (খালি রাখলে ডিফল্ট থাকবে)',
                ],

                [
                    'key' => 'tag_slug_prefix',
                    'type' => 'text',
                    'label' => 'Tag URL base',
                    'default' => 'tags',
                    'rules' => ['nullable', 'string', 'max:50'],
                    'hint' => 'Example: tags (খালি রাখলে ডিফল্ট থাকবে)',
                ],
                [
                    'key'     => 'page_slug_prefix',
                    'label'   => 'Page URL base',
                    'type'    => 'text',
                    'default' => 'page',
                    'rules'   => ['nullable', 'regex:/^[a-z0-9\\-]+$/'],
                    'hint' => 'Example: page, info, docs (খালি রাখলে ডিফল্ট থাকবে)',
                ],

                [
                    'key' => '_permalink_preview',
                    'type' => 'permalink_preview', // ✅ computed preview
                    'label' => 'Sample URL',
                ],

            ],
        ],
        'seo' => [
            'title' => 'SEO',
            'permission' => 'setting.view',
            'fields' => [
                ['key'=>'seo_default_title','label'=>'Default title','type'=>'text','default'=>'My Site','rules'=>['nullable','string','max:255']],
                ['key'=>'seo_default_description','label'=>'Default description','type'=>'textarea','default'=>'','rules'=>['nullable','string','max:500']],
                ['key'=>'seo_default_og_image','label'=>'Default OG image','type'=>'image','default'=>null,'rules'=>['nullable','string','max:255']],
                ['key'=>'seo_indexing','label'=>'Allow indexing','type'=>'switch','default'=>true,'rules'=>['boolean']],
            ],
        ],
        'cache' => [
            'title'      => 'Cache Configuration',
            'permission' => 'setting.view',
            'fields'     => [
                [
                    'key'     => 'cache_admin_menu',
                    'label'   => 'Cache Admin Menu',
                    'type'    => 'switch',
                    'default' => true,
                    'rules'   => ['boolean'],
                    'hint'    => 'Enable to speed up dashboard navigation by caching the admin menu structure.',
                ],
                [
                    'key'     => 'cache_front_menus',
                    'label'   => 'Cache Frontend Menus',
                    'type'    => 'switch',
                    'default' => true,
                    'rules'   => ['boolean'],
                    'hint'    => 'Enable to cache navigation menus rendered on the frontend for better performance.',
                ],
                [
                    'key'     => 'cache_user_avatar',
                    'label'   => 'Cache User Avatar',
                    'type'    => 'switch',
                    'default' => false,
                    'rules'   => ['boolean'],
                    'hint'    => 'Generate and store user avatar thumbnails locally until the user updates them.',
                ],
                [
                    'key'     => 'cache_shortcodes',
                    'label'   => 'Cache Shortcodes',
                    'type'    => 'switch',
                    'default' => true,
                    'rules'   => ['boolean'],
                    'hint'    => 'Enable caching for shortcode-rendered blocks to reduce processing time.',
                ],
                [
                    'key'     => 'cache_shortcode_blocks',
                    'label'   => 'Shortcode Cache Limit (Blocks)',
                    'type'    => 'number',
                    'default' => 5,
                    'rules'   => ['nullable', 'integer', 'min:1'],
                    'hint'    => 'Maximum number of shortcode blocks to cache simultaneously.',
                ],
                [
                    'key'     => 'cache_widgets',
                    'label'   => 'Cache Widgets',
                    'type'    => 'switch',
                    'default' => true,
                    'rules'   => ['boolean'],
                    'hint'    => 'Enable caching for homepage widget data to improve load speed.',
                ],
                [
                    'key'     => 'reset_cache_on_data_change',
                    'label'   => 'Auto-Clear Cache on Update',
                    'type'    => 'switch',
                    'default' => true,
                    'rules'   => ['boolean'],
                    'hint'    => 'Automatically clear relevant caches when posts, categories, pages, or menus are created or updated.',
                ],
                [
                    'key'     => 'cache_time',
                    'label'   => 'Global Cache Lifetime (Minutes)',
                    'type'    => 'number',
                    'default' => 60,
                    'rules'   => ['required', 'integer', 'min:0'],
                    'hint'    => 'The default duration (in minutes) for general caches like menus and widgets.',
                ],
                [
                    'key'     => 'sitemap_cache_time',
                    'label'   => 'Sitemap Cache Lifetime (Minutes)',
                    'type'    => 'number',
                    'default' => 60,
                    'rules'   => ['required', 'integer', 'min:0'],
                    'hint'    => 'The duration (in minutes) to keep the XML sitemap cached before regenerating.',
                ],
            ],
        ],
    ],
];
