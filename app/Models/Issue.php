<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Issue extends Model
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
                return 'Issue Jurnal telah ' . ($events[$eventName] ?? $eventName);
            });
    }
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'issue_id');
    }

    // public function editors()
    // {
    //     return $this->hasMany(Editor::class, 'issue_id');
    // }

    // public function reviewers()
    // {
    //     return $this->hasMany(Reviewer::class, 'issue_id');
    // }
}
