<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perfume extends Model
{
    protected $fillable = ['name', 'category_id'];

    public function prices(): HasMany
    {
        return $this->hasMany(PerfumePrice::class);
    }
    
    public function perfumePrices(): HasMany
    {
        return $this->hasMany(PerfumePrice::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
