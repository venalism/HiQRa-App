<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    // Menampilkan form login untuk Panitia
    public function showPanitiaLoginForm()
    {
        return view('auth.login', ['url' => 'panitia']);
    }

    // Memproses login Panitia
    public function panitiaLogin(Request $request)
    {
        $credentials = $request->validate([
            'npm' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('panitia')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/panitia/dashboard');
        }

        return back()->withErrors([
            'npm' => 'NPM atau Password salah.',
        ]);
    }

    // Menampilkan form login untuk Peserta
    public function showPesertaLoginForm()
    {
        return view('auth.login', ['url' => 'peserta']);
    }

    // Memproses login Peserta
    public function pesertaLogin(Request $request)
    {
        $credentials = $request->validate([
            'npm' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('peserta')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/peserta/dashboard');
        }

        return back()->withErrors([
            'npm' => 'NPM atau Password salah.',
        ]);
    }

    // Logout untuk semua user (panitia/peserta)
    public function logout(Request $request)
    {
        if (Auth::guard('panitia')->check()) {
            Auth::guard('panitia')->logout();
        } elseif (Auth::guard('peserta')->check()) {
            Auth::guard('peserta')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
