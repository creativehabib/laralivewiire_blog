<x-layouts.app :title="__('Menu Management')">
    <div class="h-full w-full rounded-xl">

        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl
                   border border-gray-200 dark:border-slate-700
                   bg-white dark:bg-slate-900
                   p-4 sm:p-6
                   shadow-sm"
        >
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-slate-100">
                    Menu management
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                    Create, organise and publish navigation menus across your site.
                </p>
            </div>

            <div class="dark:bg-slate-900">
                {{-- Component --}}
                <livewire:admin.menu-management />
            </div>
        </div>

    </div>
</x-layouts.app>


@push('styles')
    {{-- Nestable plugin CSS --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css"
        referrerpolicy="no-referrer"
    />

    {{-- Plugin helper styles (Tailwind-friendly colors/shapes) --}}
    <style>
        .dd {
            max-width: 100%;
        }

        .dd3-handle {
            height: 40px;
            width: 40px;
            background: var(--color-slate-600); /* Tailwind indigo-600 */
            border-radius: 0.5rem; /* rounded-lg */
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: move;
        }

        .dd3-handle:before {
            content: '\2630';
            font-size: 18px;
        }

        .dd3-content {
            margin: 0.375rem 0;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 0.5rem; /* rounded-lg */
            background: var(--color-slate-800);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08); /* subtle slate shadow */
        }

        .menu-picker {
            max-height: 240px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
            background: var(--color-slate-50);/* gray-50 */
        }

        .menu-picker .form-check {
            margin-bottom: 0.5rem;
        }

        .menu-picker .form-check:last-child {
            margin-bottom: 0;
        }
    </style>
@endpush

@push('scripts')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"
        referrerpolicy="no-referrer"
    ></script>
    <script>
        const initMenuNestable = () => {
            const $el = $('#menuNestable');

            if (!$el.length || typeof $el.nestable !== 'function') {
                return;
            }

            if ($el.data('nestable')) {
                $el.nestable('destroy');
            }

            const serializeItems = (items) => {
                return items.map(item => {
                    const children = Array.isArray(item.children) ? serializeItems(item.children) : [];

                    return {
                        id: item.id,
                        ...(children.length ? { children } : {})
                    };
                });
            };

            $el.nestable({
                maxDepth: 3,
                expandBtnHTML: '',
                collapseBtnHTML: ''
            }).on('change', function (e) {
                const list = e.length ? e : $(e.target);
                const structure = list.nestable('serialize');
                const serialized = Array.isArray(structure) ? serializeItems(structure) : [];
                Livewire.dispatch('menuOrderUpdated', { items: serialized });
            });
        };

        document.addEventListener('livewire:init', () => {
            initMenuNestable();

            Livewire.on('refreshNestable', () => {
                setTimeout(initMenuNestable, 100);
            });
        });
    </script>
@endpush
