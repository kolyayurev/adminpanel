import _ from 'lodash';

window._ = _;

window.$ = window.jQuery = require('jquery');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

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

const { Dropzone } = require("dropzone");
window.Dropzone = Dropzone;

import Cookies from 'js-cookie'
window.Cookies = Cookies;

require('nestable2');

require('./tinymce');

// window.helpers = require('./helpers.js');













