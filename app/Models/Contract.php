<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClient; 

class Contract extends Model
{
    use BelongsToClient;

    protected $table = 'contracts'; 
    protected $primaryKey = 'ID'; 
    public $timestamps = false;
    protected $guarded = [];

    // Relasi ke tabel rooms: Kontrak ini untuk kamar yang mana?
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'ID');
    }

    // Relasi ke tabel client_users: Siapa penghuni (tenant) yang menyewa?
    public function clientUser()
    {
        return $this->belongsTo('App\\Models\\ClientUser', 'client_user_id', 'ID');
    }

    // Relasi ke tabel invoices: Satu kontrak bisa memiliki banyak tagihan (sewa, listrik, dll)
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'room_assignment_id', 'ID');
    }
}