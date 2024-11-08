<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'status',
    ];

    public function owners(){
        return $this->hasMany(User::class,'market_id');
    }
}
