<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}