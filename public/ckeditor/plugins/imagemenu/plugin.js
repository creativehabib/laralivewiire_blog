CKEDITOR.plugins.add('imagemenu', {
    icons: 'imagemenu',

    init: function (editor) {

        /* ─────────────────────────────────────────────
         *  1) Insert Image via URL — Custom Dialog
         * ───────────────────────────────────────────── */
        CKEDITOR.dialog.add('imageUrlDialog', function (editor) {
            return {
                title: 'Insert image via URL',
                minWidth: 400,
                minHeight: 80,
                resizable: CKEDITOR.DIALOG_RESIZE_NONE,
                contents: [
                    {
                        id: 'info',
                        label: 'Image URL',
                        elements: [
                            {
                                type: 'text',
                                id: 'url',
                                label: 'Insert image via URL',
                                inputStyle: 'width:100%;',
                                required: true,
                                validate: CKEDITOR.dialog.validate.notEmpty("Please enter image URL."),
                                setup: function () {
                                    this.getInputElement().setAttribute(
                                        'placeholder',
                                        'https://example.com/image.png'
                                    );
                                }
                            }
                        ]
                    }
                ],
                onOk: function () {
                    var dialog = this;
                    var url = dialog.getValueOf('info', 'url') || '';
                    url = CKEDITOR.tools.trim(url);

                    if (!url) return;

                    editor.insertHtml(
                        '<img src="' + CKEDITOR.tools.htmlEncode(url) + '" alt="">'
                    );
                }
            };
        });

        editor.addCommand('imageInsertFromUrl', {
            exec: function (ed) {
                ed.openDialog('imageUrlDialog');
            }
        });

        /* ─────────────────────────────────────────────
         *  2) Image Upload From Computer
         * ───────────────────────────────────────────── */
        editor.addCommand('imageUploadFromComputer', {
            exec: function (ed) {
                let input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';

                input.onchange = function (e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const url = URL.createObjectURL(file);
                    ed.insertHtml('<img src="' + url + '" alt="">');
                };

                input.click();
            }
        });

        /* ─────────────────────────────────────────────
         *  3) Insert from Media Manager
         * ───────────────────────────────────────────── */
        editor.addCommand('imageInsertFromManager', {
            exec: function (ed) {
                if (typeof openCkeditorImagePicker === 'function') {
                    openCkeditorImagePicker(ed.name);
                } else {
                    alert('Media Manager helper (openCkeditorImagePicker) not found.');
                }
            }
        });

        /* ─────────────────────────────────────────────
         *  4) MENUBUTTON UI
         * ───────────────────────────────────────────── */
        if (editor.addMenuItems) {
            editor.addMenuGroup('imageMenuGroup');

            editor.addMenuItems({
                imageUploadFromComputer: {
                    icon: 'image',
                    label: 'Upload from computer',
                    group: 'imageMenuGroup',
                    command: 'imageUploadFromComputer',
                    order: 1
                },
                imageInsertFromManager: {
                    icon: 'image',
                    label: 'Insert with file manager',
                    group: 'imageMenuGroup',
                    command: 'imageInsertFromManager',
                    order: 2
                },
                imageInsertFromUrl: {
                    icon: 'image',
                    label: 'Insert via URL',
                    group: 'imageMenuGroup',
                    command: 'imageInsertFromUrl',
                    order: 3
                }
            });
        }

        editor.ui.add('ImageMenu', CKEDITOR.UI_MENUBUTTON, {
            label: 'Insert image',
            title: 'Insert image',
            toolbar: 'insert',

            icon: this.path + 'icons/image-plus.svg',

            onMenu: function () {
                return {
                    imageUploadFromComputer:
                        editor.getCommand('imageUploadFromComputer').state === CKEDITOR.TRISTATE_OFF,
                    imageInsertFromManager:
                        editor.getCommand('imageInsertFromManager').state === CKEDITOR.TRISTATE_OFF,
                    imageInsertFromUrl:
                        editor.getCommand('imageInsertFromUrl').state === CKEDITOR.TRISTATE_OFF,
                };
            }
        });
    }
});
