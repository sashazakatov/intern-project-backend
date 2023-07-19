<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'role',
        'password',
        'country',
        'city',
        'address',
        'phone_number',
    ];

    protected $table = 'users';
}
