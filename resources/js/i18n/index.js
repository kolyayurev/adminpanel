
import Lang from 'lang.js';
import * as messages from './messages.json';

const lang = new Lang({
    messages: messages,
    locale: locale,
    fallback: fallbackLocale
});

// global.lang = lang;
window.lang = lang;




