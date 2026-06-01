<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappChat extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'last_message_at' => 'datetime',
        'unread_count' => 'integer',
    ];

    public function whatsappAccount()
    {
        return $this->belongsTo(WhatsappAccount::class);
    }

    public function messages()
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function messagesAsc()
    {
        return $this->hasMany(WhatsappMessage::class)->orderBy('created_at', 'asc');
    }

    public function getDisplayNameAttribute()
    {
        return $this->name ?? $this->phone ?? $this->wa_id;
    }
}
