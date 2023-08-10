<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    protected $attributes = [
        'avatar' => 'http://intern-project-backend/storage/default_avatar/default_avatar.png',
    ];

    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isRegionalAdmin(){
        return $this->role === 'regional_admin';
    }
    public function isOwner(){
        return $this->role === 'owner';
    }
    public function isCustomer(){
        return $this->role === 'customer';  
    }
}
