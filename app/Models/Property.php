<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClient; 

class Property extends Model
{
    use BelongsToClient; 

    protected $table = 'properties'; // Menggunakan huruf kecil
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $guarded = [];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'Property_id', 'ID');
    }
}