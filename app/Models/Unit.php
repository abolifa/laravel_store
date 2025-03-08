<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $guarded = [];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
