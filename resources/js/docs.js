import {isValidUrl} from '@/lib';
import hljs from 'highlight.js/lib/core';
import bash from 'highlight.js/lib/languages/bash';
import css from 'highlight.js/lib/languages/css';
import javascript from 'highlight.js/lib/languages/javascript';
import php from 'highlight.js/lib/languages/php';

hljs.registerLanguage('bash', bash);
hljs.registerLanguage('css', css);
hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('php', php);
import 'highlight.js/styles/monokai.css';

$(document).ready(function () {
    $('#docs').on('click', 'a', function (e) {
        let target = e.target;
        let path = $(target).attr('href');

        if (path.endsWith('md') || !isValidUrl(path)) {
            e.preventDefault();

            let folder = $(target).parents('#content').length?$('[data-folder]').data('folder'):'';
            axios
                .post(route('adminpanel.docs.content'),
                    {
                        path: path,
                        folder: folder
                    }
                )
                .then(response => {
                    if (response.data.status === 'success') {
                        $('#content').html(response.data.content);
                        hljs.highlightAll();
                    }
                })
                .catch(e => {
                    toastr.error(e.message);
                })
        }

    });
});
