<?php

namespace App\Models;

use App\Models\Market;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image', 'price', 'desc', 'rate', 'category_id', 'market_id'];
    protected $casts = ['price' => 'integer'];
    public function Market()
    {
        return $this->belongsTo(Market::class);
    }
    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productSubCategoryValues()
    {
        return $this->hasMany(productSubCategoryValue::class,'product_id','id');
    }
}
