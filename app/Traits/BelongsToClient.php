<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 * @method static void creating(\Closure $callback)
 */
trait BelongsToClient
{
    protected static function bootBelongsToClient(): void
    {
        // 1. Otomatis filter query SELECT berdasarkan client_id aktif di session
        if (method_exists(static::class, 'addGlobalScope')) {
            call_user_func([static::class, 'addGlobalScope'], 'client_id', function (Builder $builder) {
                if (session()->has('active_client_id')) {
                    $builder->where(
                        $builder->getQuery()->from . '.client_id',
                        session('active_client_id')
                    );
                }
            });
        }

        // 2. Otomatis isi kolom client_id saat INSERT data baru
        static::creating(function ($model) {
            if (session()->has('active_client_id') && ! $model->client_id) {
                $model->client_id = session('active_client_id');
            }
        });
    }
}