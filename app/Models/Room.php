<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'property_id',
        'tipe_kamar_id',
        'nomor_kamar',
        'harga', // Mengikuti nama kolom di database
        'status',
        'foto',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'tipe_kamar_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'room_id');
    }

    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(MaintenanceTicket::class, 'room_id');
    }
}