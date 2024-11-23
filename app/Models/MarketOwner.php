<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class MarketOwner extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'email',
        'password',
        'market_id'
    ];


    public $with = [
        'market'
    ];
    protected $hidden = [
        'password',

    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
}