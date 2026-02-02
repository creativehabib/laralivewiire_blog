import CodeMirror from 'codemirror';
import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';
import { setupCodeCopy } from './modules/code-copy';

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
window.Choices = Choices;
setupCodeCopy();
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
                    label: 'Upload Image to image hippo',
                    command: 'imgHippoUpload',
                    toolbar: 'insert',
                    icon: 'image'
                });
            }
        });
    }
};

const initDeleteConfirmModal = () => {
    const deleteConfirmModal = document.querySelector('[data-delete-confirm-modal]');

    if (!deleteConfirmModal || deleteConfirmModal.dataset.deleteConfirmInitialized === 'true') {
        return;
    }

    deleteConfirmModal.dataset.deleteConfirmInitialized = 'true';

    const titleEl = deleteConfirmModal.querySelector('[data-confirm-title]');
    const messageEl = deleteConfirmModal.querySelector('[data-confirm-message]');
    const confirmButton = deleteConfirmModal.querySelector('[data-confirm-accept]');
    const cancelButtons = deleteConfirmModal.querySelectorAll('[data-confirm-cancel]');

    const defaultConfig = {
        title: 'Confirm delete',
        message: 'Do you really want to delete this record?',
        confirmText: 'Delete',
        cancelText: 'Close'
    };

    let confirmAction = null;
    let cancelAction = null;

    const openModal = ({
        title = defaultConfig.title,
        message = defaultConfig.message,
        confirmText = defaultConfig.confirmText,
        cancelText = defaultConfig.cancelText,
        onConfirm = null,
        onCancel = null
    } = {}) => {
        if (titleEl) titleEl.textContent = title;
        if (messageEl) messageEl.textContent = message;
        if (confirmButton) confirmButton.textContent = confirmText;
        cancelButtons.forEach((button) => {
            button.textContent = cancelText;
        });

        confirmAction = onConfirm;
        cancelAction = onCancel;
        deleteConfirmModal.classList.remove('hidden');
        deleteConfirmModal.classList.add('flex');
        deleteConfirmModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
    };

    const closeModal = (triggerCancel = false) => {
        deleteConfirmModal.classList.add('hidden');
        deleteConfirmModal.classList.remove('flex');
        deleteConfirmModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');

        if (triggerCancel && typeof cancelAction === 'function') {
            cancelAction();
        }

        confirmAction = null;
        cancelAction = null;
    };

    confirmButton?.addEventListener('click', () => {
        const action = confirmAction;
        closeModal();
        if (typeof action === 'function') {
            action();
        }
    });

    cancelButtons.forEach((button) => {
        button.addEventListener('click', () => closeModal(true));
    });

    deleteConfirmModal.addEventListener('click', (event) => {
        if (event.target === deleteConfirmModal) {
            closeModal(true);
        }
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !deleteConfirmModal.classList.contains('hidden')) {
            closeModal(true);
        }
    });

    window.showDeleteConfirm = (options = {}) => new Promise((resolve) => {
        openModal({
            ...options,
            onConfirm: () => resolve(true),
            onCancel: () => resolve(false)
        });
    });

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-confirm]');
        if (!trigger) return;

        if (trigger.dataset.confirmed === 'true') {
            delete trigger.dataset.confirmed;
            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();

        const message = trigger.dataset.confirm || defaultConfig.message;
        const title = trigger.dataset.confirmTitle || defaultConfig.title;
        const confirmText = trigger.dataset.confirmText || defaultConfig.confirmText;
        const cancelText = trigger.dataset.confirmCancel || defaultConfig.cancelText;

        openModal({
            title,
            message,
            confirmText,
            cancelText,
            onConfirm: () => {
                trigger.dataset.confirmed = 'true';
                const form = trigger.closest('form');

                if (trigger.tagName === 'A' && trigger.getAttribute('href')) {
                    window.location.href = trigger.href;
                    return;
                }

                if (form && trigger.type === 'submit') {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit(trigger);
                    } else {
                        form.submit();
                    }
                    return;
                }

                trigger.click();
            }
        });
    });

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-confirm]');
        if (!form) return;

        if (form.dataset.confirmed === 'true') {
            delete form.dataset.confirmed;
            return;
        }

        event.preventDefault();
        const message = form.dataset.confirm || defaultConfig.message;
        const title = form.dataset.confirmTitle || defaultConfig.title;
        const confirmText = form.dataset.confirmText || defaultConfig.confirmText;
        const cancelText = form.dataset.confirmCancel || defaultConfig.cancelText;

        openModal({
            title,
            message,
            confirmText,
            cancelText,
            onConfirm: () => {
                form.dataset.confirmed = 'true';
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            }
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDeleteConfirmModal);
} else {
    initDeleteConfirmModal();
}

document.addEventListener('livewire:navigated', initDeleteConfirmModal);
