import hljs from 'highlight.js/lib/core';
import bash from 'highlight.js/lib/languages/bash';
import css from 'highlight.js/lib/languages/css';
import javascript from 'highlight.js/lib/languages/javascript';
import php from 'highlight.js/lib/languages/php';

// Локальная копия из lib.js: чтобы docs-entry не делил общий чанк с app-entry —
// каждый бандл должен быть самодостаточным (грузится как классический <script>).
function isValidUrl(urlString) {
    var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
        '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator

    return !!urlPattern.test(urlString);
}

hljs.registerLanguage('bash', bash);
hljs.registerLanguage('css', css);
hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('php', php);
// Стили подсветки вынесены в resources/sass/docs.scss → public/css/docs.css (отдельный CSS-entry).

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
