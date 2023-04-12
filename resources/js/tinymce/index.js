import tinymce from 'tinymce/tinymce';

window.TinyMCE = window.tinymce = tinymce;

import 'tinymce/models/dom';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';

import './langs/ru';
// Plugins
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/code';
import 'tinymce/plugins/table';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/fullscreen';

window.apTinyMCE = require('./config');
