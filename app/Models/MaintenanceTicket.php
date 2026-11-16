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

    protected static function booted(): void
    {
        static::creating(function (MaintenanceTicket $ticket) {
            if (empty($ticket->tanggal_lapor)) {
                $ticket->tanggal_lapor = now();
            }
        });
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // Relasi utama ke ClientUser karena pelapor_id menyimpan client_users.id
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(ClientUser::class, 'pelapor_id');
    }

    // Relasi cadangan jika ada data lama yang menyimpan users.id langsung
    public function pelaporUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    // Helper nama pelapor agar selalu terbaca di Admin
    public function getNamaPelaporAttribute(): string
    {
        return $this->pelapor?->user?->nama
            ?? $this->pelapor?->user?->name
            ?? $this->pelaporUser?->nama
            ?? $this->pelaporUser?->name
            ?? 'Tidak diketahui';
    }
}