<?php

namespace App\Models;

use App\Models\SubCategory;
use App\Models\productSubCategoryValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategoeyProperty extends Model
{
    use HasFactory;
    protected $fillable = ['sub_category_id', 'name'];
    public function SubCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function productSubCategoryValues()
    {
        return $this->hasMany(productSubCategoryValue::class);
    }
    public $with = [
        'SubCategory'
    ];
}
