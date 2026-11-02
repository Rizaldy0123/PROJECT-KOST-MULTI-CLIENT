<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToClient; // 1. Import Trait

class Notification extends Model
{
    use BelongsToClient; // 2. Terapkan Trait

    protected $table = 'notifications';

    protected $fillable = [
        'client_id',
        'user_id',
        'tipe_notifikasi',
        'pesan',
        'status_baca',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}