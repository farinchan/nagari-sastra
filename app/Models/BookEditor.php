<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BookEditor extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('buku')
            ->setDescriptionForEvent(function (string $eventName) {
                $events = ['created' => 'ditambahkan', 'updated' => 'diperbarui', 'deleted' => 'dihapus'];
                return 'Editor Buku telah ' . ($events[$eventName] ?? $eventName);
            });
    }

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get display name: prioritize manual name, then fall back to user name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? ($this->user ? $this->user->name : '-');
    }

    /**
     * Get display name with title
     */
    public function getDisplayNameWithTitleAttribute(): ?string
    {
        return $this->name_with_title ?? ($this->user ? $this->user->name : null);
    }

    /**
     * Get display email
     */
    public function getDisplayEmailAttribute(): ?string
    {
        return $this->email ?? ($this->user ? $this->user->email : null);
    }
}
