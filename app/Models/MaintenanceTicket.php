<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceTicket extends Model
{
    protected $table = 'maintenance_tickets';
    
    // Nonaktifkan timestamps bawaan karena tabel tidak memiliki created_at dan updated_at
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

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // Relasi ke User untuk mendapatkan data pelapor
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }
}