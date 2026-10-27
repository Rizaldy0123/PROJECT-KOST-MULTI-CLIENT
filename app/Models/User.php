<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'ID';
    public $timestamps = false; // Matikan karena nama kolom Anda adalah create_at

    protected $fillable = [
        'nama',
        'email',
        'password_hash',
        'no_hp',
        'status',
    ];

    protected $hidden = [
        'password_hash',
    ];

    // Beri tahu Laravel bahwa kolom password Anda bernama 'password_hash' (bukan 'password')
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}