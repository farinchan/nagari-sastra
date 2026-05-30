<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OutgoingMailCategory extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded()
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    protected $table = 'outgoing_mail_categories';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function outgoingMails()
    {
        return $this->hasMany(OutgoingMail::class);
    }
}
