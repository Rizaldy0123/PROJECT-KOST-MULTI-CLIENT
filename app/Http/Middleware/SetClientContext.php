<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SetClientContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (is_object($user) && method_exists($user, 'clientUsers')) {
                // Ambil client_id aktif dari relasi clientUsers yang sedang aktif
                $activeClient = $user->clientUsers()->where('is_active', 1)->first();

                if ($activeClient) {
                    session(['active_client_id' => $activeClient->client_id]);
                }
            }
        }

        return $next($request);
    }
}