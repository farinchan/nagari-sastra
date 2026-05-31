<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebchatConversation extends Model
{
    protected $fillable = [
        'session_id',
        'visitor_name',
        'visitor_email',
        'status',
        'ip_address',
        'user_agent',
        'last_message_at',
        'webchat_widget_id',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function widget()
    {
        return $this->belongsTo(WebchatWidget::class);
    }

    public function messages()
    {
        return $this->hasMany(WebchatMessage::class)->orderBy('created_at', 'desc');
    }

    public function messagesAsc()
    {
        return $this->hasMany(WebchatMessage::class)->orderBy('created_at', 'asc');
    }

    public function unreadCount()
    {
        return $this->messages()->where('sender', 'visitor')->where('is_read', false)->count();
    }

    public function getDisplayNameAttribute()
    {
        return $this->visitor_name ?: 'Pengunjung #' . $this->id;
    }
}
