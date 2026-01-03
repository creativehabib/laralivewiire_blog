<div class="p-6">
    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center text-sm text-slate-500 uppercase font-semibold tracking-wider">
        <span class="text-blue-600">Dashboard</span>
        <span class="mx-2">/</span>
        <span class="text-slate-800">Comments</span>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">

        {{-- Toolbar --}}
        <div class="p-4 flex flex-col sm:flex-row justify-between items-center gap-4 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-2 w-full sm:w-auto">

                {{-- Bulk Actions Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="px-4 py-2 bg-white border border-slate-300 rounded text-slate-600 text-sm font-medium hover:bg-slate-50 flex items-center gap-2 transition-colors shadow-sm">
                        Bulk Actions <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute z-20 mt-1 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg border border-slate-200 dark:border-slate-700 py-1" style="display: none;">
                        <button wire:click="updateStatusSelected('approved')" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">Approve</button>
                        <button wire:click="updateStatusSelected('pending')" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">Mark as Pending</button>
                        <button wire:click="updateStatusSelected('spam')" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">Mark as Spam</button>
                        <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                        <button wire:click="deleteSelected" wire:confirm="Are you sure?" class="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">Move to Trash</button>
                    </div>
                </div>

                {{-- Filters --}}
                <select wire:model.live="filterStatus" class="border-slate-300 rounded text-sm text-slate-600 focus:ring-blue-500 py-2 pl-3 pr-8">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="spam">Spam</option>
                    <option value="trash">Trash</option>
                </select>

                {{-- Search --}}
                <div class="relative flex-1 sm:flex-none">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." class="pl-3 pr-10 py-2 border border-slate-300 rounded text-sm w-full sm:w-64 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute right-3 top-3 text-slate-400"></i>
                </div>
            </div>

            <button wire:click="$refresh" class="px-4 py-2 bg-white border border-slate-300 text-slate-600 rounded text-sm font-medium hover:bg-slate-50 flex items-center gap-2 shadow-sm transition-colors">
                <i class="fas fa-sync-alt"></i> Reload
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 text-[11px] uppercase text-slate-500 font-bold tracking-wider">
                    <th class="p-4 w-10 text-center">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-slate-300 focus:ring-blue-500 w-4 h-4">
                    </th>
                    <th class="p-4 w-16">ID</th>
                    <th class="p-4 w-64">Author</th>
                    <th class="p-4">Comment</th>
                    <th class="p-4 w-48">Response To</th>
                    <th class="p-4 w-32 text-center">Status</th>
                    <th class="p-4 w-32">Submitted On</th>
                    <th class="p-4 w-32 text-center">Operations</th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                @forelse($comments as $comment)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-center">
                            <input type="checkbox" wire:model.live="selected" value="{{ $comment->id }}" class="rounded border-slate-300 focus:ring-blue-500 w-4 h-4">
                        </td>
                        <td class="p-4 text-slate-500">{{ $comment->id }}</td>

                        {{-- Author Column --}}
                        <td class="p-4">
                            <div class="flex items-start gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=random&color=fff" alt="{{ $comment->name }}" class="w-10 h-10 rounded shadow-sm">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $comment->name }}</span>
                                    <a href="mailto:{{ $comment->email }}" class="text-xs text-blue-500 hover:underline">{{ $comment->email }}</a>
                                    @if($comment->website)
                                        <a href="{{ $comment->website }}" target="_blank" class="text-xs text-slate-400 hover:text-blue-500 flex items-center gap-1 mt-0.5">
                                            {{ parse_url($comment->website, PHP_URL_HOST) }} <i class="fas fa-external-link-alt text-[10px]"></i>
                                        </a>
                                    @endif
                                    <span class="text-[10px] text-slate-400 mt-1">IP: {{ $comment->ip_address }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Comment Content --}}
                        <td class="p-4">
                            <div class="space-y-1">
                                @if($comment->parent)
                                    <div class="text-xs text-slate-500">
                                        In reply to
                                        <span class="text-blue-600 dark:text-blue-400 font-medium">
                                            {{ $comment->parent->name }}
                                        </span>
                                    </div>
                                @endif
                                <div class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed line-clamp-3">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        </td>

                        {{-- Response To --}}
                        <td class="p-4">
                            @if($comment->commentable)
                                @php
                                    $commentable = $comment->commentable;
                                    $commentableTitle = $commentable->name
                                        ?? $commentable->title
                                        ?? 'Deleted Content';
                                    $commentableUrl = $commentable instanceof \App\Models\Post
                                        ? post_permalink($commentable)
                                        : ($commentable instanceof \App\Models\Admin\Page ? page_permalink($commentable) : null);
                                @endphp
                                <a href="{{ $commentableUrl ?? '#' }}" class="hover:underline text-sm font-medium line-clamp-2" title="{{ $commentableTitle }}">
                                    {{ $commentableTitle }}
                                </a>
                                @if($commentableUrl)
                                    <a href="{{ $commentableUrl }}" target="_blank" class="text-xs text-slate-400 hover:text-blue-500 block mt-1">
                                        <i class="fas fa-external-link-alt"></i> View Article
                                    </a>
                                @endif
                            @else
                                <span class="text-slate-400 italic">Content Deleted</span>
                            @endif
                        </td>

                        {{-- Status Badge --}}
                        <td class="p-4 text-center">
                            @php
                                $statusClasses = [
                                    'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'spam' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    'trash' => 'bg-slate-100 text-slate-600 border-slate-200',
                                ];
                                $label = ucfirst($comment->status);
                            @endphp
                            <span class="px-2.5 py-1 rounded text-[11px] font-bold border {{ $statusClasses[$comment->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $label }}
                            </span>
                        </td>

                        {{-- Submitted On --}}
                        <td class="p-4 text-slate-500 text-xs">
                            <div class="font-medium">{{ $comment->created_at->format('Y-m-d') }}</div>
                            <div>{{ $comment->created_at->format('H:i:s') }}</div>
                        </td>

                        {{-- Operations --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="openReplyModal({{ $comment->id }})" title="Reply" class="w-8 h-8 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-reply text-xs"></i>
                                </button>

                                {{-- Status Toggle Button --}}
                                @if($comment->status === 'approved')
                                    <button wire:click="updateStatus({{ $comment->id }}, 'pending')" title="Mark Pending" class="w-8 h-8 rounded bg-amber-500 text-white hover:bg-amber-600 transition-colors flex items-center justify-center shadow-sm">
                                        <i class="fas fa-clock text-xs"></i>
                                    </button>
                                @else
                                    <button wire:click="updateStatus({{ $comment->id }}, 'approved')" title="Approve" class="w-8 h-8 rounded bg-emerald-500 text-white hover:bg-emerald-600 transition-colors flex items-center justify-center shadow-sm">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                @endif

                                <button wire:click="delete({{ $comment->id }})" wire:confirm="Delete this comment?" title="Delete" class="w-8 h-8 rounded bg-rose-500 text-white hover:bg-rose-600 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="fas fa-comments text-4xl mb-3 opacity-50"></i>
                                <span class="text-lg font-medium">No comments found</span>
                                <p class="text-sm">Try adjusting your search or filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex items-center justify-between">
            {{-- Rows per page selector --}}
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <select wire:model.live="perPage" class="border-slate-300 rounded text-xs py-1 pl-2 pr-6 focus:ring-blue-500">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <span>records per page</span>
            </div>

            <div class="flex items-center gap-1 text-sm text-slate-500">
                <i class="fas fa-globe text-slate-400 mr-1"></i>
                Show from {{ $comments->firstItem() ?? 0 }} to {{ $comments->lastItem() ?? 0 }} in {{ $comments->total() }} records
            </div>

            <div class="pagination-simple">
                {{ $comments->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>

    @if($showReplyModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/50" wire:click="closeReplyModal"></div>
            <div class="relative w-full max-w-3xl bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">
                        Reply to {{ $replyTargetName }}
                    </h3>
                    <button type="button" wire:click="closeReplyModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="px-6 py-4">
                    <div wire:ignore>
                        <textarea id="reply-editor" class="w-full border border-slate-300 rounded-md min-h-[220px]"></textarea>
                    </div>
                    @error('replyContent')
                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40">
                    <button type="button" wire:click="closeReplyModal" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-300 rounded-md hover:bg-slate-100">
                        Cancel
                    </button>
                    <button type="button" wire:click="submitReply" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Reply
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        function initReplyEditor(content = '') {
            const editorId = 'reply-editor';

            if (!window.CKEDITOR) {
                return;
            }

            if (CKEDITOR.instances[editorId]) {
                CKEDITOR.instances[editorId].destroy(true);
            }

            if (typeof window.setupCkeditorBase === 'function') {
                window.setupCkeditorBase('{{ setting("hippo_api_key") }}');
            }

            const editor = CKEDITOR.replace(editorId, {
                height: 260,
            });

            editor.setData(content || '');

            editor.on('change', function () {
                @this.set('replyContent', editor.getData());
            });
        }

        window.addEventListener('init-reply-editor', (event) => {
            initReplyEditor(event.detail?.content || '');
        });
    </script>
@endpush
