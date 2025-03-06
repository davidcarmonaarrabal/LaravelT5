<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clients extends Model
{
    protected $fillable = ['name',
                            'identify',
                            'telephone',
                            'email',
                            'company'];
    use HasFactory;

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }
}
