<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = ['perfume_id', 'size_id', 'customer_type', 'price'];

    public function perfume(): BelongsTo
    {
        return $this->belongsTo(Perfume::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }
}
