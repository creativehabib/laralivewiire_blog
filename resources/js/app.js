import CodeMirror from 'codemirror';

// Modes
import 'codemirror/mode/css/css.js';
import 'codemirror/mode/javascript/javascript.js';

// নতুন: HTML মোড (অবশ্যই এই দুটি লাইন যোগ করবেন)
import 'codemirror/mode/xml/xml.js';
import 'codemirror/mode/htmlmixed/htmlmixed.js';
import 'codemirror/mode/shell/shell.js';

// Styles (Base)
import 'codemirror/lib/codemirror.css';

// Themes (আপনার পছন্দমত থিম ইম্পোর্ট করুন)
// import 'codemirror/theme/dracula.css';  // Dark Theme
// import 'codemirror/theme/eclipse.css';  // Light Theme
import 'codemirror/theme/monokai.css';
// import 'codemirror/theme/material.css'; // চাইলে এটাও রাখতে পারেন

// Global window object
window.CodeMirror = CodeMirror;
document.addEventListener('DOMContentLoaded', () => {
    const isInlineCode = (element) => {
        if (!(element instanceof HTMLElement)) {
            return true;
        }

        const isCodeTag = element.tagName === 'CODE';
        const isInsidePre = !!element.closest('pre');
        const hasMultilineContent = element.innerText.includes('\n');

        if (!isCodeTag) {
            return false;
        }

        if (isInsidePre) {
            return false;
        }

        const displayStyle = window.getComputedStyle(element).display;

        return displayStyle === 'inline' && !hasMultilineContent;
    };

    const getCopyTargets = () => {
        const blocks = document.querySelectorAll('.ck-content pre, .ck-content code');
        const uniqueBlocks = new Set();

        blocks.forEach((block) => {
            const container = block.closest('pre') ?? block;
            uniqueBlocks.add(container);
        });

        return Array.from(uniqueBlocks).filter((block) => !isInlineCode(block));
    };

    const copyTextToClipboard = async (text) => {
        if (!text) return false;

        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (error) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'absolute';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();

            const success = document.execCommand('copy');
            document.body.removeChild(textarea);
            return success;
        }
    };

    const initCodeCopy = () => {
        const codeBlocks = getCopyTargets();

        codeBlocks.forEach((block) => {
            if (block.querySelector('.code-copy-btn')) {
                return;
            }

            block.classList.add('code-block-container');

            const copyButton = document.createElement('button');
            copyButton.type = 'button';
            copyButton.className = 'code-copy-btn';
            copyButton.innerHTML = '<i class="fa-regular fa-copy"></i><span class="sr-only">কপি করুন</span>';

            copyButton.addEventListener('click', async () => {
                const codeElement = block.querySelector('code');
                const code = codeElement?.innerText ?? block.innerText;

                const copied = await copyTextToClipboard(code.trim());

                if (copied) {
                    copyButton.classList.add('copied');
                    copyButton.innerHTML = '<i class="fa-solid fa-check"></i><span class="sr-only">কপি সম্পন্ন</span>';
                } else {
                    copyButton.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i><span class="sr-only">কপি ব্যর্থ</span>';
                }

                setTimeout(() => {
                    copyButton.classList.remove('copied');
                    copyButton.innerHTML = '<i class="fa-regular fa-copy"></i><span class="sr-only">কপি করুন</span>';
                }, 1500);
            });

            block.appendChild(copyButton);
        });
    };

    const highlightCodeBlocks = () => {
        if (window.Prism?.highlightAll) {
            window.Prism.highlightAll();
        }
    };

    const observeCodeBlocks = () => {
        const observer = new MutationObserver((mutations) => {
            let shouldInit = false;
            let shouldHighlight = false;

            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (!(node instanceof HTMLElement)) {
                        return;
                    }

                    if (
                        node.matches?.('.ck-content pre, .ck-content code') ||
                        node.querySelector?.('.ck-content pre, .ck-content code')
                    ) {
                        shouldInit = true;
                        shouldHighlight = true;
                    }
                });
            });

            if (shouldInit) {
                initCodeCopy();
            }

            if (shouldHighlight) {
                highlightCodeBlocks();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    };

    initCodeCopy();
    highlightCodeBlocks();
    observeCodeBlocks();

    const confirmDialog = (() => {
        let container = null;
        let confirmHandler = null;
        let cancelHandler = null;
        let overlayHandler = null;
        let escapeHandler = null;

        const ensureContainer = () => {
            if (container) return container;

            container = document.createElement('div');
            container.id = 'app-confirm-dialog';
            container.className = 'fixed inset-0 z-[1200] hidden';
            container.innerHTML = `
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-confirm-overlay></div>
                <div class="relative min-h-full flex items-center justify-center px-4 py-8">
                    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700" role="dialog" aria-modal="true" aria-labelledby="confirm-dialog-title">
                        <div class="p-6 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                                    <i class="fas fa-triangle-exclamation"></i>
                                </div>
                                <div class="space-y-1">
                                    <h3 id="confirm-dialog-title" class="text-lg font-semibold text-slate-900 dark:text-slate-50" data-confirm-title>Confirm action</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-300" data-confirm-message>Are you sure?</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <button type="button" class="px-4 py-2 rounded-lg bg-rose-600 text-white font-semibold hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800" data-confirm-accept>Delete</button>
                                <button type="button" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 dark:text-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800" data-confirm-cancel>Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(container);
            return container;
        };

        const close = () => {
            if (!container) return;

            container.classList.add('hidden');

            const acceptButton = container.querySelector('[data-confirm-accept]');
            const cancelButton = container.querySelector('[data-confirm-cancel]');
            const overlay = container.querySelector('[data-confirm-overlay]');

            if (confirmHandler) acceptButton?.removeEventListener('click', confirmHandler);
            if (cancelHandler) cancelButton?.removeEventListener('click', cancelHandler);
            if (overlayHandler) overlay?.removeEventListener('click', overlayHandler);
            if (escapeHandler) window.removeEventListener('keydown', escapeHandler);

            confirmHandler = null;
            cancelHandler = null;
            overlayHandler = null;
            escapeHandler = null;
        };

        const open = ({ title, message, confirmText, cancelText, onConfirm }) => {
            const dialog = ensureContainer();
            const titleEl = dialog.querySelector('[data-confirm-title]');
            const messageEl = dialog.querySelector('[data-confirm-message]');
            const acceptButton = dialog.querySelector('[data-confirm-accept]');
            const cancelButton = dialog.querySelector('[data-confirm-cancel]');
            const overlay = dialog.querySelector('[data-confirm-overlay]');

            if (titleEl) titleEl.textContent = title || 'Confirm action';
            if (messageEl) messageEl.textContent = message || 'Are you sure?';
            if (acceptButton) acceptButton.textContent = confirmText || 'Delete';
            if (cancelButton) cancelButton.textContent = cancelText || 'Cancel';

            dialog.classList.remove('hidden');
            acceptButton?.focus();

            confirmHandler = () => {
                onConfirm?.();
                close();
            };

            cancelHandler = () => close();
            overlayHandler = () => close();
            escapeHandler = (event) => {
                if (event.key === 'Escape') close();
            };

            acceptButton?.addEventListener('click', confirmHandler);
            cancelButton?.addEventListener('click', cancelHandler);
            overlay?.addEventListener('click', overlayHandler);
            window.addEventListener('keydown', escapeHandler);
        };

        return { open };
    })();

    document.addEventListener('click', (event) => {
        const target = event.target.closest('[wire\\:confirm]');
        if (!target) return;

        if (target.dataset.confirmed === 'true') {
            delete target.dataset.confirmed;
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        const message = target.getAttribute('wire:confirm') || 'Are you sure?';
        const title = target.getAttribute('data-confirm-title') || 'Confirm action';
        const confirmText = target.getAttribute('data-confirm-cta') || 'Delete';
        const cancelText = target.getAttribute('data-cancel-text') || 'Cancel';

        confirmDialog.open({
            title,
            message,
            confirmText,
            cancelText,
            onConfirm: () => {
                target.dataset.confirmed = 'true';
                target.click();
            },
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initCodeCopy();
        highlightCodeBlocks();
    });
    window.addEventListener('post-content-loaded', () => {
        initCodeCopy();
        highlightCodeBlocks();
    });

    document.addEventListener('livewire:init', () => {
        if (window.Livewire && typeof window.Livewire.hook === 'function') {
            window.Livewire.hook('message.processed', () => {
                initCodeCopy();
                highlightCodeBlocks();
            });
        }
    });

    window.addEventListener('load', highlightCodeBlocks);
});
