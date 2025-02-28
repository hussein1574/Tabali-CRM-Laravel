<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;


class LoginController extends Controller
{
    public function index(): View
    {
        return view('login');
    }
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->remember)) {
            if (!Auth::user()->is_activated) {
                $this->logout($request);
                if (App::isLocale('en')) {
                    return back()->withErrors([
                        'active' => 'Your account is not activated yet. Please contact the Admin!'
                    ])->onlyInput('email');
                } else {
                    return back()->withErrors([
                        'active' => 'لم يتم تفعيل حسابك بعد. يرجى التواصل مع المسؤول!'
                    ])->onlyInput('email');
                }
            }
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        if (App::isLocale('en')) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        } else {
            return back()->withErrors([
                'email' => 'بيانات الدخول هذه غير متطابقة للبيانات المسجلة لدينا.',
            ])->onlyInput('email');
        }
    }
    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
