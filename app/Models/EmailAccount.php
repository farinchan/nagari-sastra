<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmailAccount extends Model
{
    use LogsActivity;

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded()
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'imap_host' => 'encrypted',
        'imap_username' => 'encrypted',
        'imap_password' => 'encrypted',
        'smtp_host' => 'encrypted',
        'smtp_username' => 'encrypted',
        'smtp_password' => 'encrypted',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'last_synced_at' => 'datetime',
        'imap_folders' => 'array',
    ];

    public function emailMessages()
    {
        return $this->hasMany(EmailMessage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImapConfig(): array
    {
        return [
            'host' => $this->imap_host,
            'port' => $this->imap_port,
            'encryption' => $this->imap_encryption,
            'username' => $this->imap_username,
            'password' => $this->imap_password,
        ];
    }

    public function getSmtpConfig(): array
    {
        return [
            'host' => $this->smtp_host,
            'port' => $this->smtp_port,
            'encryption' => $this->smtp_encryption,
            'username' => $this->smtp_username,
            'password' => $this->smtp_password,
        ];
    }
}
