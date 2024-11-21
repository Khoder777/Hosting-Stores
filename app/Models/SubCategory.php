<?php

namespace App\Models;

use App\Models\SubCategoeyProperty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'category_id'
    ];

    public $with = [
        'category'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function SubCategoeyProperties()
    {
        return $this->hasMany(SubCategoeyProperty::class);
    }
}