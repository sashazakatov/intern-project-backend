<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, HasApiTokens;

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
