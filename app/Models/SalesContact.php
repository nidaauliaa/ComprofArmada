<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesContact extends Model
{
    protected $fillable = ['name', 'phone', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }
}