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
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('persuratan')
            ->setDescriptionForEvent(function (string $eventName) {
                $events = ['created' => 'ditambahkan', 'updated' => 'diperbarui', 'deleted' => 'dihapus'];
                return 'Kategori Surat Keluar telah ' . ($events[$eventName] ?? $eventName);
            });
    }

    protected $table = 'outgoing_mail_categories';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function outgoingMails()
    {
        return $this->hasMany(OutgoingMail::class);
    }
}
