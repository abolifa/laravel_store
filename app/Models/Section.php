<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = [];


    protected $casts = [
        'categories' => 'array',
        'products' => 'array',
    ];
}
