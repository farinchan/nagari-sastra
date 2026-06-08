<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ChateryContact extends Model
{
    use LogsActivity;

    protected $fillable = ['chatery_contact_group_id', 'name', 'phone'];

    public function group()
    {
        return $this->belongsTo(ChateryContactGroup::class, 'chatery_contact_group_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
