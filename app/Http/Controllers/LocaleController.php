<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(string $locale, Request $request)
    {
        if (! in_array($locale, ['pt', 'en'], true)) {
            return back();
        }

        // Cookie persistente (1 ano) lido pelo middleware SetLocale em qualquer rota,
        // inclusive 404, antes mesmo do session stack.
        return back()->withCookie(
            cookie('gestschool_locale', $locale, 60 * 24 * 365, raw: true)
        );
    }
}
