<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function emailAccount()
    {
        return $this->belongsTo(EmailAccount::class);
    }

    public function group()
    {
        return $this->belongsTo(EmailGroup::class, 'email_group_id');
    }

    public function logs()
    {
        return $this->hasMany(EmailCampaignLog::class);
    }

    public function getProgressAttribute()
    {
        if ($this->total_recipients == 0) return 0;
        return round(($this->sent_count / $this->total_recipients) * 100, 1);
    }
}
