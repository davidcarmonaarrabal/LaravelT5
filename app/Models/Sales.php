<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sales extends Model
{
    protected $fillable = ['total',
                            'payment'];
    
    use HasFactory;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class);
    }

    public function item_sales(): HasMany
    {
        return $this->hasMany(ItemSales::class);
    }
}
