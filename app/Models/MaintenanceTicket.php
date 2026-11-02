<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToClient; // 1. Import Trait

class MaintenanceTicket extends Model
{
    use BelongsToClient; // 2. Terapkan Trait

    protected $table = 'maintenance_tickets';

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'room_id',
        'pelapor_id',
        'judul',
        'deskripsi',
        'status',
        'tanggal_lapor',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(ClientUser::class, 'pelapor_id');
    }
}