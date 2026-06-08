<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NewsCategory extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('berita')
            ->setDescriptionForEvent(function (string $eventName) {
                $events = ['created' => 'ditambahkan', 'updated' => 'diperbarui', 'deleted' => 'dihapus'];
                return 'Kategori Berita telah ' . ($events[$eventName] ?? $eventName);
            });
    }

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function news()
    {
        return $this->hasMany(News::class);
    }
}
