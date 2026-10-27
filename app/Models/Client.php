<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients'; // Menggunakan huruf kecil sesuai phpMyAdmin
    protected $primaryKey = 'ID'; 
    public $timestamps = false;   
    protected $guarded = [];      

    public function properties()
    {
        return $this->hasMany(Property::class, 'client_id', 'ID');
    }
}
