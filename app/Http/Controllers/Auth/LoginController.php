<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /** Mostra o formulário de login. */
    public function show(): View
    {
        return view('auth.login');
    }

    /** Valida as credenciais e inicia a sessão. */
    public function login(Request $request): RedirectResponse
    {
        $credenciais = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Informe o e-mail.',
            'email.email'       => 'Esse e-mail não parece válido.',
            'password.required' => 'Informe a senha.',
        ]);

        if (! Auth::attempt($credenciais, $request->boolean('remember'))) {
            // Mensagem genérica de propósito: não revela se o e-mail existe.
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'E-mail ou senha incorretos.']);
        }

        // Evita fixação de sessão: gera um ID novo após o login.
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /** Encerra a sessão. */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
