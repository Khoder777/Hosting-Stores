<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;
    protected $fillable=[
        'city',
        'shipping',
        'status'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class,'city_id');
    }

    
}
