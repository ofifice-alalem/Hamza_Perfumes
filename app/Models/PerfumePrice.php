<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfumePrice extends Model
{
    protected $fillable = ['perfume_id', 'size_id', 'bottle_size', 'bottle_price_regular', 'bottle_price_vip', 'price_regular', 'price_vip'];

    public function perfume(): BelongsTo
    {
        return $this->belongsTo(Perfume::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }
}
