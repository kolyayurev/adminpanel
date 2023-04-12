/**
 * https://github.com/tighten/ziggy
 */

import Router from './Router';
import { Routes } from './routes';


window.route = function (name, params, absolute) {
    return route(name, params, absolute, Routes);
}
export default function route(name, params, absolute, config) {
    const router = new Router(name, params, absolute, config);

    return name ? router.toString() : router;
}
