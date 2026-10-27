<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClient;

class RoomType extends Model
{
    use BelongsToClient; 

    protected $table = 'room_types'; // Menggunakan huruf kecil
    protected $primaryKey = 'ID'; 
    public $timestamps = false;
    protected $guarded = [];
}
