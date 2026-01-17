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
