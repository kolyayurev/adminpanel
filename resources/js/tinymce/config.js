/*--------------------
|
| TinyMCE default config
|
--------------------*/

var getConfig = function(options) {

    const image_upload_handler = (blobInfo, progress) => new Promise((resolve, reject) => {
        let data = new FormData();
        data.append('file', blobInfo.blob(), blobInfo.filename());
        data.append("upload_path", '/texteditor/images/');


        $.ajax({
            url: route('adminpanel.media.upload'),
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status) {
                    toastr.success(result.message, lang.get('common.sweet_success'));
                    resolve(storage+result.path);
                } else {
                    toastr.error(result.message, lang.get('common.whoopsie'));
                    reject('HTTP Error: ' + result.message);
                }
            }
        });
    });
    const baseTinymceConfig = {
        menubar: false,
        selector: 'textarea.text-editor',
        skin: ($('html').data('bs-theme') === 'dark' ? 'oxide-dark' : 'oxide'),
        content_css: $('meta[name="assets-path"]').attr('content')+'?path=js/skins/content/'+($('html').data('bs-theme') === 'dark' ? 'dark' : 'default')+'/content.css',//($('html').data('bs-theme') === 'dark' ? 'dark' : 'default'),
        skin_url: $('meta[name="assets-path"]').attr('content')+'?path=js/skins/ui/'+($('html').data('bs-theme') === 'dark' ? 'oxide-dark' : 'oxide'),
        min_height: 300,
        resize: 'vertical',
        plugins: 'link, image, code, table,  lists, fullscreen',
        extended_valid_elements : 'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick],span[*]',
        toolbar1: 'styleselect bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | code | fullscreen',
        toolbar2: '',
        formats: {
            span: { inline: 'span'}
        },
        convert_urls: false,
        image_caption: true,
        image_title: true,
        language: locale,
        init_instance_callback: function (editor) {
            if (typeof tinymce_init_callback === "function") {
                tinymce_init_callback(editor);
            }
        },
        setup: function (editor) {
            editor.ui.registry.addToggleButton('wrapSpan', {
                text: 'span',
                onAction: (api) => {
                    editor.execCommand('mceToggleFormat', false, 'span');
                }
            });
            if (typeof tinymce_setup_callback === "function") {
                tinymce_setup_callback(editor);
            }
        },
        file_picker_types: 'image',
        images_upload_handler: image_upload_handler,
    };
    return $.extend({}, baseTinymceConfig, options);
}

exports.getConfig = getConfig;
