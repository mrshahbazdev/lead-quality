<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(string $locale)
    {
        $supportedLocales = ['en', 'es', 'fr', 'de', 'zh', 'ja', 'pt', 'ru', 'ar', 'hi'];

        if (in_array($locale, $supportedLocales)) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
