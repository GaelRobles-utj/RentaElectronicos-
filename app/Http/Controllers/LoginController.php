<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'usuario' => 'required|string',
                'contrasena' => 'required|string',
            ],
            [
                'usuario.required' => 'Ingresa tu usuario.',
                'contrasena.required' => 'Ingresa tu contraseña.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('usuario'));
        }

        $admin = Administrador::where(
            'usuario',
            $request->input('usuario')
        )->first();

        if ($admin && (int) $admin->estado !== 1) {
            return back()
                ->withErrors([
                    'error' => 'Esta cuenta se encuentra desactivada.',
                ])
                ->withInput($request->only('usuario'));
        }

        $credenciales = [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('contrasena'),
            'estado' => 1,
        ];

        if (Auth::guard('admin')->attempt($credenciales)) {
            $request->session()->regenerate();

            return redirect()->intended(
                route('dashboard')
            );
        }

        return back()
            ->withErrors([
                'error' => 'Usuario o contraseña incorrectos.',
            ])
            ->withInput($request->only('usuario'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}