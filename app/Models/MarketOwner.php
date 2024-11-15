<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketOwner extends Model
{
    use HasFactory;

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
