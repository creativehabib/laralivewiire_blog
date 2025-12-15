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
                ],
                [
                    'key' => 'logo',
                    'label' => 'Logo',
                    'type' => 'image', // তোমার mediamanager picker দিয়ে হবে
                    'default' => null,
                    'rules' => ['nullable','string','max:255'],
                ],
                [
                    'key' => 'timezone',
                    'label' => 'Timezone',
                    'type' => 'select',
                    'default' => 'Asia/Dhaka',
                    'options' => [
                        'Asia/Dhaka' => 'Asia/Dhaka',
                        'UTC' => 'UTC',
                        'Asia/Kolkata' => 'Asia/Kolkata',
                    ],
                    'rules' => ['required','string'],
                ],
                [
                    'key' => 'maintenance_mode',
                    'label' => 'Maintenance mode',
                    'type' => 'switch',
                    'default' => false,
                    'rules' => ['boolean'],
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
                    'key' => 'category_slug_prefix_enabled',
                    'type' => 'switch',
                    'label' => 'Category URL prefix',
                    'default' => true,
                    'rules' => ['nullable'],
                    'hint' => 'Category URL এ "category" prefix থাকবে?',
                ],

                [
                    'key' => 'tag_slug_prefix',
                    'type' => 'text',
                    'label' => 'Tag URL base',
                    'default' => 'tags',
                    'rules' => ['required', 'string', 'max:50'],
                    'hint' => 'Example: tags',
                ],
                [
                    'key'     => 'page_slug_prefix',
                    'label'   => 'Page URL base',
                    'type'    => 'text',
                    'default' => 'page',
                    'rules'   => ['required', 'regex:/^[a-z0-9\-]+$/'],
                    'hint' => 'Example: page, info, docs',
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
    ],
];
