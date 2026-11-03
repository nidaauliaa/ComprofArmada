<?php
namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use Translatable;

    protected $guarded = ['id'];

    public function models(): HasMany
    {
        return $this->hasMany(ProductModel::class);
    }

    /** Gambar bawaan (storage/app/public/images) bila kategori belum punya gambar unggahan. */
    private const DEFAULT_POSTERS = [
        'big-bus' => 'images/BIGBUS-DOUBLEDECKER.png',
        'medium-bus' => 'images/MEDIUM-BUS.png',
        'intercity' => 'images/INTERCITY-2.png',
        'microbus' => 'images/MICROBUS-2.png',
        'box-heavy-duty' => 'images/BOX.png',
    ];

    public function posterPath(): ?string
    {
        if ($this->image_path && \App\Support\StorageImage::exists($this->image_path)) {
            return $this->image_path;
        }

        return self::DEFAULT_POSTERS[$this->slug] ?? ($this->image_path ? \App\Support\StorageImage::guess($this->slug) : null);
    }

    public function posterUrl(): ?string
    {
        $path = $this->posterPath();

        return $path ? asset('storage/'.$path) : null;
    }
}
