<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Items extends Model
{
    protected $fillable = ['name', 'product_id'];
    use HasFactory;

    public function item(): BelongsTo
    {
        return $this->belongsTo(Items::class);
    }

    public function item_sale(): HasMany
    {
        return $this->hasMany(ItemSales::class);
    }
}
