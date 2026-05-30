<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramChat extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_blocked' => 'boolean',
        'last_message_at' => 'datetime',
        'chat_id' => 'integer',
    ];

    public function bot()
    {
        return $this->belongsTo(TelegramBot::class, 'telegram_bot_id');
    }

    public function messages()
    {
        return $this->hasMany(TelegramMessage::class);
    }

    public function getDisplayNameAttribute()
    {
        if ($this->first_name || $this->last_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        }
        if ($this->username) {
            return '@' . $this->username;
        }
        if ($this->title) {
            return $this->title;
        }
        return 'Chat #' . $this->chat_id;
    }
}
