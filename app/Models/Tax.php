<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tax extends Model
{
    protected $guarded = [];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_tax')->withPivot('amount')->withTimestamps();
    }
}
