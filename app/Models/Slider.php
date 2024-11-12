<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Slider extends Model
{
    use HasFactory;
    protected $casts = [
        'status' => 'boolean',
        'type' => 'string',
        'image' => 'string',
    ];
    protected $fillable = [

        'status',
        'type',
        'image',
    ];
}
