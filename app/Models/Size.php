<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    protected $fillable = ['label'];

    public function prices(): HasMany
    {
        return $this->hasMany(PerfumePrice::class);
    }
    
    public function perfumePrices(): HasMany
    {
        return $this->hasMany(PerfumePrice::class);
    }
    
    public function categoryPrices(): HasMany
    {
        return $this->hasMany(CategoryPrice::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
