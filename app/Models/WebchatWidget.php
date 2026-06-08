<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WebchatWidget extends Model
{
    use LogsActivity;

    protected $fillable = [
        'token',
        'name',
        'allowed_domains',
        'primary_color',
        'secondary_color',
        'greeting_message',
        'header_title',
        'header_subtitle',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('crm')
            ->setDescriptionForEvent(function (string $eventName) {
                $events = ['created' => 'ditambahkan', 'updated' => 'diperbarui', 'deleted' => 'dihapus'];
                return 'Widget Webchat telah ' . ($events[$eventName] ?? $eventName);
            });
    }

    protected static function booted()
    {
        static::creating(function ($widget) {
            if (empty($widget->token)) {
                $widget->token = 'wgt_' . Str::random(24);
            }
            if (is_null($widget->greeting_message)) {
                $widget->greeting_message = 'Halo! 👋 Selamat datang. Ada yang bisa kami bantu?';
            }
        });
    }

    public function conversations()
    {
        return $this->hasMany(WebchatConversation::class);
    }

    public function getAllowedDomainsArrayAttribute()
    {
        if (!$this->allowed_domains) return [];
        return array_map('trim', explode(',', $this->allowed_domains));
    }
}
