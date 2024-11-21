<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productSubCategoryValue extends Model
{
    use HasFactory;
    protected $fillable = ['value', 'product_id', 'sub_category_property_id', 'image'];
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
    public function SubCategoeyProperty()
    {
        return $this->belongsTo(SubCategoeyProperty::class, 'sub_category_property_id', 'id');
    }
}
