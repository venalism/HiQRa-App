<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserSettingsController extends Controller
{
    public function showPanitiaSettingsForm()
    {
        return view('panitia.settings', ['user' => Auth::user()]);
    }

    public function updatePanitia(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:15', 'unique:panitia,no_hp,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->nama = $request->nama;
        $user->no_hp = $request->no_hp;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('status', 'Profil berhasil diperbarui!');
    }

    public function showPesertaSettingsForm()
    {
        return view('peserta.settings', ['user' => Auth::user()]);
    }

    public function updatePeserta(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:15', 'unique:peserta,no_hp,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->nama = $request->nama;
        $user->no_hp = $request->no_hp;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        return back()->with('status', 'Profil berhasil diperbarui!');
    }
}
