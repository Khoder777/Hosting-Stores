<?php

namespace App\Models;

use App\Models\Market;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
    ];


    public function subCategories()
    {
        return $this->hasMany(subCategory::class);
    }
    public function Products()
    {
        return $this->hasMany(Product::class);
    }
    public function Market()
    {
        return $this->belongsTo(Market::class);
    }
}