<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'owner_id',
        'administrator_id',
        'name',
        'device_type',
        'phase_active',
        'phase_type',
        'sum_power',
        'group_id',
        'location',
        'country',
        'city',
        'address',
        'owner_email'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, 'administrator_id');
    }
}
