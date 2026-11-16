<?php

namespace App\Traits;

use App\Models\ClientUser;
use Illuminate\Support\Facades\Auth;

trait ResolvesPenghuni
{
    /**
     * Ambil data client_user (penghuni aktif) dari akun yang sedang login.
     */
    protected function currentPenghuni(): ClientUser
    {
        return ClientUser::where('user_id', Auth::id())
            ->where('role', 'penghuni')
            ->where('is_active', 1)
            ->firstOrFail();
    }
}
