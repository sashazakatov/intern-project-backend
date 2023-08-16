<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'administrator_id'
    ];

    public function administrator()
    {
        return $this->belongsTo(User::class, 'administrator_id');
    }
}
