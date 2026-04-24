<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        if (Session::get('role') !== $role) {
            if ($role === 'admin') {
                return redirect('/login')->with('error', 'Akses ditolak. Halaman ini khusus admin.');
            }
            return redirect('/admin/login')->with('error', 'Akses ditolak. Halaman ini khusus user.');
        }

        return $next($request);
    }
}

