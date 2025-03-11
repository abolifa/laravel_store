<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
//
//    public function city(): BelongsTo
//    {
//        return $this->belongsTo(City::class);
//    }

    public function shippings(): HasMany
    {
        return $this->hasMany(Shipping::class);
    }
}
