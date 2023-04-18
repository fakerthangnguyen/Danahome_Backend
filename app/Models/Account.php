<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Account extends Authenticatable
{
    use HasFactory,HasApiTokens,Notifiable;
    protected $fillable = [
        'full_name',
        'password',
        'email',
        'phone_number',
        'image',
        'type',
        'role_id',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function room2(){
        return $this->hasMany(Room::class);
    }
}
