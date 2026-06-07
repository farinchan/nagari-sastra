<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SettingWebsite extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded()
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Accessor: $setting_web->logo → full Storage URL
     */
    public function getLogoAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        if (Str::startsWith(trim($value), ['http://', 'https://'])) {
            return $value;
        }
        return Storage::url($value);
    }

    /**
     * Accessor: $setting_web->favicon → full Storage URL
     */
    public function getFaviconAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        if (Str::startsWith(trim($value), ['http://', 'https://'])) {
            return $value;
        }
        return Storage::url($value);
    }

    /**
     * Get logo URL (kept for backward compatibility)
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * Get favicon URL (kept for backward compatibility)
     */
    public function getFavicon(): ?string
    {
        return $this->favicon;
    }

    public function getAboutRaw(){
        return strip_tags($this->about);
    }
}
