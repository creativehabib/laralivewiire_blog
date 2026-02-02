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

const deleteConfirmState = {
    modal: null,
    titleEl: null,
    messageEl: null,
    confirmButton: null,
    cancelButtons: [],
    confirmAction: null,
    cancelAction: null,
    defaultConfig: {
        title: 'Confirm delete',
        message: 'Do you really want to delete this record?',
        confirmText: 'Delete',
        cancelText: 'Close'
    }
};

const setDeleteConfirmModal = () => {
    const deleteConfirmModal = document.querySelector('[data-delete-confirm-modal]');
    if (!deleteConfirmModal) {
        return false;
    }

    deleteConfirmState.modal = deleteConfirmModal;
    deleteConfirmState.titleEl = deleteConfirmModal.querySelector('[data-confirm-title]');
    deleteConfirmState.messageEl = deleteConfirmModal.querySelector('[data-confirm-message]');
    deleteConfirmState.confirmButton = deleteConfirmModal.querySelector('[data-confirm-accept]');
    deleteConfirmState.cancelButtons = Array.from(deleteConfirmModal.querySelectorAll('[data-confirm-cancel]'));
    return true;
};

const openDeleteConfirm = ({
    title = deleteConfirmState.defaultConfig.title,
    message = deleteConfirmState.defaultConfig.message,
    confirmText = deleteConfirmState.defaultConfig.confirmText,
    cancelText = deleteConfirmState.defaultConfig.cancelText,
    onConfirm = null,
    onCancel = null
} = {}) => {
    if (!deleteConfirmState.modal) {
        return false;
    }

    if (deleteConfirmState.titleEl) deleteConfirmState.titleEl.textContent = title;
    if (deleteConfirmState.messageEl) deleteConfirmState.messageEl.textContent = message;
    if (deleteConfirmState.confirmButton) deleteConfirmState.confirmButton.textContent = confirmText;
    deleteConfirmState.cancelButtons.forEach((button) => {
        button.textContent = cancelText;
    });

    deleteConfirmState.confirmAction = onConfirm;
    deleteConfirmState.cancelAction = onCancel;
    deleteConfirmState.modal.classList.remove('hidden');
    deleteConfirmState.modal.classList.add('flex');
    deleteConfirmState.modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
    return true;
};

const closeDeleteConfirm = (triggerCancel = false) => {
    if (!deleteConfirmState.modal) {
        return;
    }

    deleteConfirmState.modal.classList.add('hidden');
    deleteConfirmState.modal.classList.remove('flex');
    deleteConfirmState.modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');

    if (triggerCancel && typeof deleteConfirmState.cancelAction === 'function') {
        deleteConfirmState.cancelAction();
    }

    deleteConfirmState.confirmAction = null;
    deleteConfirmState.cancelAction = null;
};

const bindDeleteConfirmListeners = () => {
    if (window.__deleteConfirmListenersBound) {
        return;
    }

    window.__deleteConfirmListenersBound = true;

    document.addEventListener('click', (event) => {
        const confirmButton = event.target.closest('[data-confirm-accept]');
        const cancelButton = event.target.closest('[data-confirm-cancel]');

        if (confirmButton && deleteConfirmState.modal?.contains(confirmButton)) {
            event.preventDefault();
            const action = deleteConfirmState.confirmAction;
            closeDeleteConfirm();
            if (typeof action === 'function') {
                action();
            }
            return;
        }

        if (cancelButton && deleteConfirmState.modal?.contains(cancelButton)) {
            event.preventDefault();
            closeDeleteConfirm(true);
        }
    }, true);

    document.addEventListener('click', (event) => {
        if (deleteConfirmState.modal && event.target === deleteConfirmState.modal) {
            event.preventDefault();
            closeDeleteConfirm(true);
        }
    }, true);

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && deleteConfirmState.modal && !deleteConfirmState.modal.classList.contains('hidden')) {
            closeDeleteConfirm(true);
        }
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
        event.stopPropagation();

        const message = trigger.dataset.confirm || deleteConfirmState.defaultConfig.message;
        const title = trigger.dataset.confirmTitle || deleteConfirmState.defaultConfig.title;
        const confirmText = trigger.dataset.confirmText || deleteConfirmState.defaultConfig.confirmText;
        const cancelText = trigger.dataset.confirmCancel || deleteConfirmState.defaultConfig.cancelText;

        const proceed = () => {
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
        };

        if (!openDeleteConfirm({ title, message, confirmText, cancelText, onConfirm: proceed })) {
            if (window.confirm(message)) {
                proceed();
            }
        }
    }, true);

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-confirm]');
        if (!form) return;

        if (form.dataset.confirmed === 'true') {
            delete form.dataset.confirmed;
            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();
        event.stopPropagation();

        const message = form.dataset.confirm || deleteConfirmState.defaultConfig.message;
        const title = form.dataset.confirmTitle || deleteConfirmState.defaultConfig.title;
        const confirmText = form.dataset.confirmText || deleteConfirmState.defaultConfig.confirmText;
        const cancelText = form.dataset.confirmCancel || deleteConfirmState.defaultConfig.cancelText;

        const proceed = () => {
            form.dataset.confirmed = 'true';
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        };

        if (!openDeleteConfirm({ title, message, confirmText, cancelText, onConfirm: proceed })) {
            if (window.confirm(message)) {
                proceed();
            }
        }
    }, true);
};

const initDeleteConfirmModal = () => {
    setDeleteConfirmModal();
    bindDeleteConfirmListeners();
    window.showDeleteConfirm = (options = {}) => new Promise((resolve) => {
        if (!openDeleteConfirm({
            ...options,
            onConfirm: () => resolve(true),
            onCancel: () => resolve(false)
        })) {
            resolve(window.confirm(options?.message || deleteConfirmState.defaultConfig.message));
        }
    });
};

const observeDeleteConfirmModal = () => {
    if (window.__deleteConfirmObserver || !document.body) {
        return;
    }

    window.__deleteConfirmObserver = new MutationObserver(() => {
        initDeleteConfirmModal();
    });

    window.__deleteConfirmObserver.observe(document.body, {
        childList: true,
        subtree: true
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDeleteConfirmModal);
} else {
    initDeleteConfirmModal();
}

document.addEventListener('livewire:navigated', initDeleteConfirmModal);
document.addEventListener('livewire:initialized', initDeleteConfirmModal);
document.addEventListener('livewire:load', initDeleteConfirmModal);
document.addEventListener('livewire:init', () => {
    if (window.Livewire?.hook) {
        window.Livewire.hook('message.processed', () => {
            initDeleteConfirmModal();
        });
    }
});
observeDeleteConfirmModal();
