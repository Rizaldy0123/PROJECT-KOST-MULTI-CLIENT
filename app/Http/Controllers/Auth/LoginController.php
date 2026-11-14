<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login menggunakan kolom password_hash via getAuthPassword() di Model User
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 1. Cek status keaktifan user
            if ($user->status !== 'aktif') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda dalam status nonaktif atau pending.']);
            }

            $request->session()->regenerate();

            // 2. Ambil konteks client_user aktif
            $clientUser = null;

            if (method_exists($user, 'clientUsers')) {
                $clientUser = $user->clientUsers()->where('is_active', 1)->first();
            }

            if (!$clientUser) {
                $candidateTables = ['client_users', 'client_user', 'user_clients'];

                foreach ($candidateTables as $table) {
                    if (Schema::hasTable($table)) {
                        $clientUser = DB::table($table)
                            ->where('user_id', $user->id)
                            ->where('is_active', 1)
                            ->first();

                        if ($clientUser) {
                            break;
                        }
                    }
                }
            }

            if ($clientUser) {
                // Set konteks client_id di session untuk isolasi data
                session(['active_client_id' => $clientUser->client_id]);

                // Redirect langsung berdasarkan role client (tanpa intended agar tidak tersesat)
                if (($clientUser->role ?? null) === 'admin_client') {
                    return redirect('/admin/dashboard');
                } elseif (($clientUser->role ?? null) === 'penghuni') {
                    return redirect()->route('penghuni.dashboard');
                }
            }

            // Redirect default jika user adalah Super Admin Platform
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan tidak cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}