<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;      // Tambahkan ini
use Illuminate\Support\Facades\Session;   // Tambahkan ini
use App\Models\ClientUser;                // Tambahkan ini

class SetClientContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika ada user yang sedang login
        if (Auth::check()) {
            // Cari data user tersebut di tabel client_users
            $clientUser = ClientUser::where('user_id', Auth::id())->first();
            
            // Jika ketemu, simpan client_id miliknya ke dalam session
            if ($clientUser) {
                Session::put('client_id', $clientUser->client_id);
            }
        }

        return $next($request);
    }
}