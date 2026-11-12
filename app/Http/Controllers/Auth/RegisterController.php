<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClientUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'no_hp'     => 'required|string|max:20',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        // Gunakan 'password_hash' sesuai dengan struktur database tim Anda
        $user = User::create([
            'nama'          => $request->nama,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password), 
            'role'          => 'penghuni',
        ]);

        // Hubungkan ke tabel client_users
        ClientUser::create([
            'user_id'   => $user->id,
            'client_id' => 1,          // ID kos/properti utama
            'role'      => 'penghuni',
            'is_active' => 1,          
        ]);

        Auth::login($user);

        return redirect()->route('penghuni.dashboard')
            ->with('success', 'Akun berhasil dibuat!');
    }
}