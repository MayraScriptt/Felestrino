<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['email', 'password']);

        if (! Auth::attempt($credentials, true)) {
            return back()
                ->withErrors(['email' => 'Credenciais invalidas.'])
                ->withInput();
        }

        $request->session()->regenerate();

        if (! $request->user()?->is_admin) {
            Auth::logout();

            return back()->withErrors(['email' => 'Conta sem permissao administrativa.']);
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
