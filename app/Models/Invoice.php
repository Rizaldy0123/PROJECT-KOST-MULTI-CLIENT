<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan import ini

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'client_id',
        'room_assignment_id', // Foreign key ke tabel contracts
        'jenis_tagihan',
        'jumlah',
        'tanggal_jatuh_tempo',
        'status',
    ];

    public function contract(): BelongsTo
    {
        // Tentukan foreign key 'room_assignment_id' secara eksplisit
        return $this->belongsTo(Contract::class, 'room_assignment_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}