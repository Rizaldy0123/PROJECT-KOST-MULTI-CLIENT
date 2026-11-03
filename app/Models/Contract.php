<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $table = 'contracts';

    protected $fillable = [
        'room_id',
        'client_id',
        'client_user_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        // Ubah ke ClientUser::class karena foreign key client_user_id merujuk ke tabel client_users
        return $this->belongsTo(ClientUser::class, 'client_user_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'room_assignment_id');
    }
}