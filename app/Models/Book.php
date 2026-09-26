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
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('buku')
            ->setDescriptionForEvent(function (string $eventName) {
                $events = ['created' => 'ditambahkan', 'updated' => 'diperbarui', 'deleted' => 'dihapus'];
                return 'Buku telah ' . ($events[$eventName] ?? $eventName);
            });
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

    /**
     * Clean author name by stripping academic degrees and honorifics.
     * Google Scholar strictly requires clean author names (e.g. "John Doe" or "Doe, John")
     * without titles like Prof, Dr, S.Pd, M.Kom, Ph.D, etc.
     */
    public static function cleanAuthorName(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        $name = trim($name);

        // Remove academic prefixes (e.g., Prof., Dr., Dra., Drs., Ir., H., Hj., Apt., Ns.)
        $name = preg_replace('/^(?:(?:prof|dr|dra|drs|ir|h|hj|apt|ns)\.?\s+)+/i', '', $name);

        // Remove academic suffixes after comma (e.g., ", M.Kom", ", S.Pd., M.Pd.", ", Ph.D.")
        $name = preg_replace('/,\s*(?:[A-Z][a-z0-9]?\.[A-Za-z0-9\.]*|Ph\.?D\.?|M\.?Sc\.?|B\.?Sc\.?|M\.?A\.?|B\.?A\.?|M\.?Eng\.?|M\.?Si\.?|S\.?Si\.?|M\.?M\.?|S\.?E\.?|S\.?Ked\.?|dr\.?|Sp\.?[A-Za-z0-9\.]*).*$/i', '', $name);

        // Remove multiple consecutive spaces
        $name = preg_replace('/\s+/', ' ', $name);

        return trim($name);
    }

    public function getAuthorAttribute(): ?string
    {
        $authors = $this->bookAuthors;
        if ($authors && $authors->count() > 0) {
            return $authors->pluck('name_with_title')
                ->filter()
                ->implode(', ') ?: $authors->pluck('name')->filter()->implode(', ');
        }

        return $this->attributes['author'] ?? null;
    }

    /**
     * Clean author names suitable for Google Scholar citation_author tags
     */
    public function getCitationAuthorsAttribute(): array
    {
        $authors = $this->bookAuthors;
        if ($authors && $authors->count() > 0) {
            $cleaned = $authors->map(function ($author) {
                $name = $author->name ?: $author->name_with_title;
                return self::cleanAuthorName($name);
            })->filter()->values()->all();

            if (!empty($cleaned)) {
                return $cleaned;
            }
        }

        // Fallback to legacy or direct author attribute if present
        $rawAuthor = $this->attributes['author'] ?? null;
        if ($rawAuthor) {
            $parts = preg_split('/\s*(?:;|dan|&)\s*/i', $rawAuthor);
            $cleaned = [];
            foreach ($parts as $part) {
                $clean = self::cleanAuthorName($part);
                if (!empty($clean)) {
                    $cleaned[] = $clean;
                }
            }
            return $cleaned;
        }

        return [];
    }

    /**
     * Structured author metadata including affiliation for Google Scholar
     */
    public function getCitationAuthorsDataAttribute(): array
    {
        $authors = $this->bookAuthors;
        if ($authors && $authors->count() > 0) {
            $data = [];
            foreach ($authors as $author) {
                $cleanName = self::cleanAuthorName($author->name ?: $author->name_with_title);
                if (!empty($cleanName)) {
                    $data[] = [
                        'name' => $cleanName,
                        'affiliation' => $author->affiliation ? trim($author->affiliation) : null,
                        'email' => $author->email ? trim($author->email) : null,
                    ];
                }
            }
            if (!empty($data)) {
                return $data;
            }
        }

        // Fallback to citation_authors without affiliation
        return array_map(fn($name) => ['name' => $name, 'affiliation' => null, 'email' => null], $this->citation_authors);
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
        return url(Storage::url($this->attachment));
    }

    /**
     * Resolve the direct absolute PDF URL for Google Scholar indexing.
     * Google Scholar crawlers require a direct URL to the PDF file.
     */
    public function getCitationPdfUrl(): ?string
    {
        if ($this->preview_file) {
            return $this->getPreviewFile();
        }

        if ($this->attachment) {
            $ext = strtolower(pathinfo($this->attachment, PATHINFO_EXTENSION));
            if ($ext === 'pdf' || Str::contains(strtolower($this->attachment), '.pdf')) {
                return $this->getAttachment();
            }
        }

        return null;
    }

    public function editors()
    {
        return $this->belongsToMany(User::class, 'book_editors', 'book_id', 'user_id');
    }

    public function bookEditors()
    {
        return $this->hasMany(BookEditor::class)->orderBy('order');
    }

    public function bookAuthors()
    {
        return $this->hasMany(BookAuthor::class)->orderBy('order');
    }

}
