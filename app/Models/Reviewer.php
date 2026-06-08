<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Reviewer extends Model
{
    use  LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('jurnal')
            ->setDescriptionForEvent(function (string $eventName) {
                $events = ['created' => 'ditambahkan', 'updated' => 'diperbarui', 'deleted' => 'dihapus'];
                return 'Reviewer Jurnal telah ' . ($events[$eventName] ?? $eventName);
            });
    }
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    public function submissionsReviewed()
    {
        return $this->hasMany(SubmissionReviewer::class, 'reviewer_id');
    }


    public function user()
    {
        return $this->hasOne(User::class, 'reviewer_id', 'reviewer_id');
    }
}


