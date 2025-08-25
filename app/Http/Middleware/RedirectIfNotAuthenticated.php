<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Jika tidak ada guard spesifik, gunakan 'web' sebagai default
        $guards = empty($guards) ? ['web'] : $guards;

        foreach ($guards as $guard) {
            if (!Auth::guard($guard)->check()) {
                // Jika tidak terautentikasi, arahkan ke halaman login yang sesuai
                switch ($guard) {
                    case 'panitia':
                        return redirect()->route('panitia.login');
                    case 'peserta':
                        return redirect()->route('peserta.login');
                    default:
                        // Asumsi guard 'web' atau lainnya adalah untuk admin
                        return redirect()->route('admin.login');
                }
            }
        }

        return $next($request);
    }
}
