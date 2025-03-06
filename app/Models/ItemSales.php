<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemSales extends Model
{
    protected $fillable = [ 'items_id', 'sales_id'];
    use HasFactory;

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Items::class);
    }
}
