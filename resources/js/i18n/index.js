
import Lang from 'lang.js';
// default-импорт: Vite отдаёт весь JSON одним объектом, а `import * as` не даёт доступ
// к ключам-неидентификаторам ("ru.common" и т.п.), из-за чего lang.js не находил переводы.
import messages from './messages.json';

const lang = new Lang({
    messages: messages,
    locale: locale,
    fallback: fallbackLocale
});

// global.lang = lang;
window.lang = lang;




