<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebchatMessage extends Model
{
    protected $fillable = [
        'webchat_conversation_id',
        'sender',
        'admin_user_id',
        'message',
        'image',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function conversation()
    {
        return $this->belongsTo(WebchatConversation::class, 'webchat_conversation_id');
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}
