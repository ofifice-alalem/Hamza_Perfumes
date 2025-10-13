<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = ['user_id', 'perfume_id', 'size_id', 'customer_type', 'is_full_bottle', 'price'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function perfume(): BelongsTo
    {
        return $this->belongsTo(Perfume::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }
}
