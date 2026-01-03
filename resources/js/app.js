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
/**
 * CKEditor Custom Plugins & Helpers (Reusable)
 */
window.setupCkeditorBase = function(hippoApiKey) {
    if (typeof CKEDITOR === 'undefined') return;

    // ১. ইমেজ পিকার হেল্পার (প্লাগইন এর বাইরে ব্যবহারের জন্য)
    if (typeof window.openCkeditorImagePicker !== 'function') {
        window.openCkeditorImagePicker = function(editorId) {
            if (typeof openMediaManagerForEditor !== 'function') return;
            openMediaManagerForEditor(function (url, data) {
                const editor = CKEDITOR.instances[editorId];
                if (!editor) return;
                const selection = editor.getSelection();
                const element = selection?.getStartElement?.();

                if (element && element.getName() === 'img') {
                    element.setAttribute('src', url);
                    if (data?.name) element.setAttribute('alt', data.name);
                } else {
                    editor.insertHtml(`<img src="${url}" alt="${data?.name || ''}" class="w-full object-cover"/>`);
                }
            });
        };
    }

    // ২. ImageManager প্লাগইন রেজিস্ট্রেশন
    if (!CKEDITOR.plugins.get('ImageManager')) {
        CKEDITOR.plugins.add('ImageManager', {
            init: function(editor) {
                editor.addCommand('openImageManager', {
                    exec: (ed) => window.openCkeditorImagePicker(ed.name)
                });
                editor.ui.addButton('ImageManager', {
                    label: 'Media Manager',
                    command: 'openImageManager',
                    toolbar: 'insert',
                    icon: '/assets/icons/image-plus.svg'
                });
            }
        });
    }

    // ৩. ImgHippoUploader প্লাগইন রেজিস্ট্রেশন
    if (!CKEDITOR.plugins.get('ImgHippoUploader')) {
        CKEDITOR.plugins.add('ImgHippoUploader', {
            init: function (editor) {
                editor.addCommand('imgHippoUpload', {
                    exec: async function (ed) {
                        const input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*';
                        input.onchange = async (e) => {
                            const file = e.target.files?.[0];
                            if (!file) return;
                            const formData = new FormData();
                            formData.append('api_key', hippoApiKey);
                            formData.append('file', file);
                            ed.showNotification('Uploading to ImgHippo...', 'info');
                            try {
                                const res = await fetch('https://api.imghippo.com/v1/upload', { method: 'POST', body: formData });
                                const payload = await res.json();
                                if (payload?.success) {
                                    ed.insertHtml(`<img src="${payload.data.view_url || payload.data.url}" class="w-full object-cover"/>`);
                                    ed.showNotification('Image Uploaded!', 'success');
                                }
                            } catch (err) { ed.showNotification('Upload Failed!', 'warning'); }
                        };
                        input.click();
                    }
                });
                editor.ui.addButton('ImgHippoUpload', {
                    label: 'Upload Image',
                    command: 'imgHippoUpload',
                    toolbar: 'insert',
                    icon: 'image'
                });
            }
        });
    }
};
