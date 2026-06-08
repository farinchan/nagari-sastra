<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ChateryContactGroup extends Model
{
    use LogsActivity;

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded()
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    protected $fillable = ['name', 'description'];

    public function contacts()
    {
        return $this->hasMany(ChateryContact::class);
    }
}
