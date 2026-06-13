<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('produk')
            ->setDescriptionForEvent(function (string $eventName) {
                $events = ['created' => 'ditambahkan', 'updated' => 'diperbarui', 'deleted' => 'dihapus'];
                return 'Produk telah ' . ($events[$eventName] ?? $eventName);
            });
    }

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'price' => 'float',
        'discount_price' => 'float',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'tags' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function screenshots()
    {
        return $this->hasMany(ProductScreenshot::class)->orderBy('order');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function orderItems()
    {
        return $this->hasMany(ProductOrderItem::class);
    }

    public function getThumbnail()
    {
        if (!$this->thumbnail) {
            return 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg';
        }
        if (Str::startsWith(trim($this->thumbnail), ['http://', 'https://'])) {
            return $this->thumbnail;
        }
        return Storage::url($this->thumbnail);
    }

    public function getFile()
    {
        if (!$this->file) {
            return null;
        }
        if (Str::startsWith(trim($this->file), ['http://', 'https://'])) {
            return $this->file;
        }
        return Storage::url($this->file);
    }

    public function getEffectivePrice()
    {
        if ($this->discount_price && $this->discount_price > 0) {
            return $this->discount_price;
        }
        return $this->price;
    }

    public function getDiscountPercentage()
    {
        if (!$this->discount_price || $this->discount_price <= 0 || $this->price <= 0) {
            return 0;
        }
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getAverageRating()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
