<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'nama_tenant',
        'domain',
        'alamat',
        'kontak',
        'aktif',
    ];

    public function clientUsers(): HasMany
    {
        return $this->hasMany(ClientUser::class, 'client_id');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'client_id');
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class, 'client_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(MaintenanceTicket::class, 'client_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'client_id');
    }
}