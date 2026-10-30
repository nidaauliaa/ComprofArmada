<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelGallery extends Model
{
    protected $guarded = ['id'];

    public function productModel(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class);
    }
}
