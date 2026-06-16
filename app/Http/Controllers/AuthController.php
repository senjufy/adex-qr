<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session()->has('admin_authenticated')) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $password = config('app.admin_password');

        if (!$password) {
            return back()->withErrors(['password' => 'Admin password not set in system.']);
        }

        if ($request->input('password') === $password) {
            session(['admin_authenticated' => true]);
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['password' => 'Invalid password.']);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('login');
    }
}
