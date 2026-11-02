<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToClient; // 1. Import Trait

class RoomType extends Model
{
    use BelongsToClient; // 2. Terapkan Trait

    protected $table = 'room_types';

    protected $fillable = [
        'client_id',
        'nama_tipe',
        'deskripsi',
        'harga_default',
        'kapasitas',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'tipe_kamar_id');
    }
}