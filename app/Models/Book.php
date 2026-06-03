<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Book extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'publish_year' => 'integer',
        'pages' => 'integer',
        'weight' => 'float',
        'price' => 'float',
        'stock' => 'integer',
        'keywords' => 'array',
    ];

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function getAuthorAttribute(): ?string
    {
        $authors = $this->bookAuthors;
        if ($authors && $authors->count() > 0) {
            return $authors->pluck('name_with_title')
                ->filter()
                ->implode(', ') ?: $authors->pluck('name')->filter()->implode(', ');
        }

        return null;
    }

    public function getCitationAuthorsAttribute(): array
    {
        $authors = $this->bookAuthors;
        if ($authors && $authors->count() > 0) {
            return $authors->pluck('name')->filter()->values()->all();
        }

        return [];
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

    public function getPreviewFile()
    {
        if (!$this->preview_file) {
            return null;
        }
        if (Str::startsWith(trim($this->preview_file), ['http://', 'https://'])) {
            return $this->preview_file;
        }
        return url(Storage::url($this->preview_file));
    }

    public function getAttachment()
    {
        if (!$this->attachment) {
            return null;
        }
        if (Str::startsWith(trim($this->attachment), ['http://', 'https://'])) {
            return $this->attachment;
        }
        return Storage::url($this->attachment);
    }

    public function editors()
    {
        return $this->belongsToMany(User::class, 'book_editors', 'book_id', 'user_id');
    }

    public function bookAuthors()
    {
        return $this->hasMany(BookAuthor::class)->orderBy('order');
    }

    public function invoices()
    {
        return $this->hasMany(PaymentInvoice::class);
    }
}
