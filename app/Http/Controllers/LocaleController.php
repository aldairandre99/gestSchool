<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(string $locale, Request $request)
    {
        if (in_array($locale, ['pt', 'en'], true)) {
            session(['locale' => $locale]);
        }

        return back();
    }
}
