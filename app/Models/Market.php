<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\MarketOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Market extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'status',
    ];
  

    public function MarketOwners()
    {
        return $this->hasMany(MarketOwner::class,);
    }
}