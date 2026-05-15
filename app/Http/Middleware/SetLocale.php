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

        // Apenas cookie persistente (não usa session) — para correr cedo no pipeline
        // e funcionar em rotas sem sessão (e.g. 404, rotas API).
        $locale = $request->cookie('gestschool_locale') ?: config('app.locale');

        if (! in_array($locale, $supported, true)) {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
