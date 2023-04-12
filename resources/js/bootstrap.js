import _ from 'lodash';

window._ = _;

window.$ = window.jQuery = require('jquery');
// require("jquery-ui/ui/widgets/sortable");

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

window.Popper = require('popper.js').default;

window.Cropper = require('cropperjs');

window.Swal = require('sweetalert2');

window.bootstrap = require('bootstrap');
require('bootstrap-fileinput/js/fileinput.min');
require('bootstrap-fileinput/js/locales/ru');
require('select2');

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.toastr = require('toastr');
toastr.options.preventDuplicates = true;

window.moment = require('moment');

// require('daterangepicker/moment.min');
require('daterangepicker/daterangepicker');

// import ClassicEditor from '@ckeditor/ckeditor5-build-classic/build/ckeditor';
// window.ClassicEditor = ClassicEditor;

const { Dropzone } = require("dropzone");
window.Dropzone = Dropzone;

import Cookies from 'js-cookie'
window.Cookies = Cookies;

require('nestable2');

require('./tinymce');

// window.helpers = require('./helpers.js');













