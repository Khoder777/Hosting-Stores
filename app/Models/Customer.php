<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;


class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'full_name',
        'email',
        'image',
        'phone_number',
        'city_id',
        'password',
        'status',
        'otp',
        'verified_email',
    ];

    public function Ship()
    {
        return $this->belongsTo(ship::class, 'city_id');
    }
}