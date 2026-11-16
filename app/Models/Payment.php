<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'client_user_id',
        'metode_pembayaran',
        'jumlah',
        'bukti_pembayaran',
        'status',
        'tanggal_bayar',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function clientUser(): BelongsTo
    {
        return $this->belongsTo(ClientUser::class, 'client_user_id');
    }

    // Accessor kompatibilitas untuk view Admin (index & receipt)
    public function getJumlahBayarAttribute()
    {
        return $this->jumlah;
    }

    public function getBuktiTransferAttribute()
    {
        return $this->bukti_pembayaran;
    }

    public function getNomorPembayaranAttribute(): string
    {
        return 'PAY-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }
}