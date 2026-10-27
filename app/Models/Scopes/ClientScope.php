<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session; // Tambahkan baris ini wajib ada

class ClientScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Menyaring data berdasarkan client_id yang aktif di session
        if (Session::has('client_id')) {
            $builder->where($model->getTable() . '.client_id', Session::get('client_id'));
        }
    }
}