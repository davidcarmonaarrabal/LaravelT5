<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Products extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'categoria_id'];

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Categories::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Items::class);
    }
}
