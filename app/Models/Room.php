<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms'; // Menggunakan huruf kecil
    protected $primaryKey = 'ID'; 
    public $timestamps = false;
    protected $guarded = [];

    public function property()
    {
        return $this->belongsTo(Property::class, 'Property_id', 'ID');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'tipe_kamar_id', 'ID');
    }
}
