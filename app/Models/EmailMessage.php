<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailMessage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'to_email' => 'array',
        'cc_email' => 'array',
        'is_seen' => 'boolean',
        'is_starred' => 'boolean',
        'has_attachment' => 'boolean',
        'email_date' => 'datetime',
    ];

    public function emailAccount()
    {
        return $this->belongsTo(EmailAccount::class);
    }

    public function scopeForAccount($query, $accountId)
    {
        return $query->where('email_account_id', $accountId);
    }

    public function scopeForFolder($query, $folder = 'INBOX')
    {
        return $query->where('folder', $folder);
    }
}
