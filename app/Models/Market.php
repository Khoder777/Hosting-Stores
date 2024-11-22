<?php

namespace App\Models;


use App\Models\Category;
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
    public function Categories()
    {
        return $this->hasMany(Category::class);
    }
}
