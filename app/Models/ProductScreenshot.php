<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductScreenshot extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImage()
    {
        if (!$this->image) {
            return 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg';
        }
        if (Str::startsWith(trim($this->image), ['http://', 'https://'])) {
            return $this->image;
        }
        return Storage::url($this->image);
    }
}
