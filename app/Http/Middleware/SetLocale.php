<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supported = ['pt', 'en'];
        $locale = session('locale', config('app.locale'));

        if (! in_array($locale, $supported, true)) {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
