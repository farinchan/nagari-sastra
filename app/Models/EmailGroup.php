<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmailGroup extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('crm')
            ->setDescriptionForEvent(function (string $eventName) {
                $events = ['created' => 'ditambahkan', 'updated' => 'diperbarui', 'deleted' => 'dihapus'];
                return 'Grup Email telah ' . ($events[$eventName] ?? $eventName);
            });
    }
    protected $guarded = ['id'];

    public function contacts()
    {
        return $this->hasMany(EmailContact::class);
    }

    public function campaigns()
    {
        return $this->hasMany(EmailCampaign::class);
    }
}
