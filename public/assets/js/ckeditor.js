// public/js/ckeditor.js

// ------------------------------------------------------------
// মিডিয়া ম্যানেজার → CKEditor-এ ইমেজ ইনসার্ট করার হেল্পার
// গ্লোবাল করে দিচ্ছি যাতে Blade থেকে onclick="openCkeditorImagePicker('content')" কাজ করে
// ------------------------------------------------------------
window.openCkeditorImagePicker = function (editorId) {
    if (typeof openMediaManagerForEditor !== 'function') {
        console.error('openMediaManagerForEditor() not found');
        return;
    }

    openMediaManagerForEditor(function (url, data) {
        const editor = CKEDITOR.instances[editorId];
        if (!editor) return;

        const selection = editor.getSelection();
        const element =
            selection && selection.getStartElement
                ? selection.getStartElement()
                : null;

        if (element && element.getName && element.getName() === 'img') {
            // আগে থেকে থাকা ইমেজ আপডেট করলে
            element.setAttribute('src', url);
            if (data && data.name) {
                element.setAttribute('alt', data.name);
            }
        } else {
            // নতুন ইমেজ ইনসার্ট
            editor.insertHtml(
                '<img src="' + url + '" alt="' + (data?.name || '') + '"/>'
            );
        }
    });
};

// ------------------------------------------------------------
// কাস্টম প্লাগইন: ImageManager বাটন
// গার্ড দিয়ে রেখেছি যেন একাধিকবার add করলে error না দেয়
// ------------------------------------------------------------
if (!CKEDITOR.plugins.get('ImageManager')) {
    CKEDITOR.plugins.add('ImageManager', {
        icons: 'image-plus',
        init: function (editor) {
            editor.addCommand('openImageManager', {
                exec: function (ed) {
                    window.openCkeditorImagePicker(ed.name);
                },
            });

            editor.ui.addButton('ImageManager', {
                label: 'Media Manager',
                command: 'openImageManager',
                toolbar: 'insert',
                icon: '/assets/icons/image-plus.svg', // চাইলে এখানে তোমার নিজের আইকন পাথ দিও
            });
        },
    });
}

// ------------------------------------------------------------
// মূল ফাংশন: কোনো textarea + Livewire model-এর জন্য CKEditor init
// ------------------------------------------------------------
window.initCkeditor = function (textareaId, livewire, modelName) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;

    if (typeof CKEDITOR === 'undefined') {
        console.error('CKEDITOR not found. Make sure ckeditor.js is loaded before this file.');
        return;
    }

    // আগের instance থাকলে destroy
    if (CKEDITOR.instances[textareaId]) {
        CKEDITOR.instances[textareaId].destroy(true);
    }

    const editor = CKEDITOR.replace(textareaId, {
        mathJaxLib:
            '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 300,
        uiColor: '',
        removePlugins: 'cloudservices,uploadimage,uploadfile',
        extraPlugins:
            'imagemenu,mathjax,tableresize,wordcount,notification,ImageManager,codesnippet,embed',

        // // RemoveFormat কনফিগ (চাইলে রাখতে পারো, কাজ ভালো হয়)
        // removeFormatTags:
        //     'b,big,code,del,dfn,em,font,i,ins,kbd,mark,q,s,samp,small,span,strike,strong,sub,sup,tt,u,var',
        // removeFormatAttributes:
        //     'class,style,lang,width,height,align,hspace,valign',
        //
        // // কিছু core স্টাইল mapping (optional)
        // coreStyles_bold: { element: 'strong', overrides: 'b' },
        // coreStyles_italic: { element: 'em', overrides: 'i' },
        // coreStyles_underline: { element: 'u' },
        // coreStyles_strike: { element: 's' },

        wordcount: { showCharCount: true, showWordCount: true },

        toolbar: [
            { items: ['Undo', 'Redo'] },
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'document', items: ['Source', '-', 'Preview'] },
            {
                name: 'clipboard',
                items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
            },
            {
                name: 'editing',
                items: [
                    'Find',
                    'Replace',
                    '-',
                    'SelectAll',
                    '-',
                    'RemoveFormat',
                    'CopyFormatting',
                ],
            },
            {
                name: 'basicstyles',
                items: [
                    'Bold',
                    'Italic',
                    'Underline',
                    'Strike',
                    'Subscript',
                    'Superscript',
                    'ImageManager',
                ],
            },
            {
                name: 'paragraph',
                items: [
                    'NumberedList',
                    'BulletedList',
                    '-',
                    'Outdent',
                    'Indent',
                    '-',
                    'Blockquote',
                    '-',
                    'JustifyLeft',
                    'JustifyCenter',
                    'JustifyRight',
                    'JustifyBlock',
                    'BidiLtr',
                    'BidiRtl',
                ],
            },
            { name: 'links', items: ['Link', 'Unlink'] },
            {
                name: 'insert',
                items: [
                    'Image',
                    'Table',
                    'HorizontalRule',
                    'SpecialChar',
                    'Mathjax',
                    '-',
                    'Iframe',
                    'Smiley',
                    'ImageMenu',
                    'CodeSnippet',
                    'EasyImage',
                    'Embed',
                ],
            },
            { name: 'colors', items: ['TextColor', 'BGColor', 'ShowBlocks'] },
            { name: 'tools', items: ['Maximize'] },
        ],

        allowedContent: true,
        extraAllowedContent: '*(*){*}',
    });

    // Livewire sync – debounced, যাতে অতিরিক্ত রিকোয়েস্ট না হয়
    const updateContent = CKEDITOR.tools.debounce(function (data) {
        if (!livewire) return;

        // Livewire v3 proxy @this.set
        if (typeof livewire.set === 'function') {
            livewire.set(modelName, data);
        }
        // কিছু কেসে $set থাকে
        else if (typeof livewire.$set === 'function') {
            livewire.$set(modelName, data);
        }
    }, 400);

    editor.on('change', function (e) {
        updateContent(e.editor.getData());
    });
};
