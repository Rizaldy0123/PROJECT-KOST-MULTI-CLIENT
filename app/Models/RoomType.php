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
        'nama_tipe',
        'harga_default',
        'kapasitas',
        'deskripsi',
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