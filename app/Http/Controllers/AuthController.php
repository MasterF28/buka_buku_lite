<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ===================== USER LOGIN (NPM + Password) =====================
    public function showUserLogin()
    {
        return view('auth.login');
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'npm' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('npm', $request->npm)
                    ->orWhere('nim', $request->npm)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'NPM atau password salah.');
        }

        if ($user->role !== 'mahasiswa') {
            return back()->with('error', 'Akun ini bukan akun mahasiswa.');
        }

        Session::put('user_id', $user->id);
        Session::put('nim', $user->nim ?? $user->npm);
        Session::put('role', $user->role);
        Session::put('name', $user->name);

        return redirect('/books');
    }

    // ===================== ADMIN LOGIN (Username + Password) =====================
    public function showAdminLogin()
    {
        return view('auth.admin_login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Username atau password salah.');
        }

        if ($user->role !== 'admin') {
            return back()->with('error', 'Akun ini bukan akun admin.');
        }

        Session::put('user_id', $user->id);
        Session::put('username', $user->username);
        Session::put('role', $user->role);
        Session::put('name', $user->name);

        return redirect('/admin/dashboard');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}

