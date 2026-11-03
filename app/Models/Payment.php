<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'client_id',
        'invoice_id',
        'nomor_pembayaran',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'bukti_transfer',
        'catatan',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}