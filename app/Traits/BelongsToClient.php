<?php

namespace App\Traits;

use App\Models\Client;
use App\Models\Scopes\ClientScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Model
 */
trait BelongsToClient
{
    protected static function bootBelongsToClient(): void
    {
        // Menerapkan global scope untuk filter data berdasarkan client_id
        /** @noinspection PhpUndefinedMethodInspection */
        static::addGlobalScope(new ClientScope());

        // Otomatis mengisi client_id saat data baru dibuat
        static::creating(function (Model $model): void {
            if (session()->has('client_id')) {
                $model->client_id = session()->get('client_id');
            }
        });
    }

    // Relasi ke tabel clients
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'ID');
    }
}