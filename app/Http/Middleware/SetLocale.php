<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

/**
 * Menentukan bahasa tampilan: ?lang=xx -> session -> cookie -> default.
 * Halaman admin selalu Bahasa Indonesia.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locales = array_keys(config('site.locales'));
        $default = config('site.default_locale', 'id');

        if ($request->is('admin', 'admin/*')) {
            $locale = $default;
        } else {
            $pick = $request->query('lang');
            if ($pick && in_array($pick, $locales, true) && $request->hasSession()) {
                $request->session()->put('locale', $pick);
            }

            $locale = $request->hasSession() ? $request->session()->get('locale') : null;
            $locale = $locale ?: $request->cookie('locale');
            if (! in_array($locale, $locales, true)) {
                $locale = $default;
            }
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
