<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'google_id',
        'photo',
        'name',
        'sinta_id',
        'scopus_id',
        'google_scholar',
        'username',
        'phone',
        'email',
        'password',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Check if user is currently online (active within last 5 minutes).
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->greaterThan(now()->subMinutes(5));
    }

    /**
     * Get formatted last seen text.
     */
    public function lastSeenFormatted(): string
    {
        if (!$this->last_seen_at) {
            return 'Belum pernah login';
        }

        if ($this->isOnline()) {
            return 'Online';
        }

        return $this->last_seen_at->diffForHumans();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function getPhoto()
    {
        if ($this->photo && (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://'))) {
            return $this->photo;
        }
        return $this->photo ? asset('storage/' . $this->photo) : "https://ui-avatars.com/api/?background=15365F&color=C3A356&size=128&name=" . $this->name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
