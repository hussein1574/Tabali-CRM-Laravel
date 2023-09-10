<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function changeLocale(Request $request)
    {
        $locale = $request->locale;

        if (!in_array($locale, ['en', 'ar'])) {
            abort(400);
        }
        session()->put('locale', $locale);
        return response()->json(['message' => 'Locale changed']);
    }
}
