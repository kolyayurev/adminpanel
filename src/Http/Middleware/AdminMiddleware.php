<?php

namespace KY\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        auth()->setDefaultDriver(app('AdminPanelGuard'));

        if (!Auth::guest()) {
//            $user = Auth::user();
            // app()->setLocale($user->locale ?? app()->getLocale());
            return Gate::allows('view_admin') ? $next($request) : redirect('/');
        }

        $urlLogin = route('adminpanel.login');

        return redirect()->guest($urlLogin);
    }
}
