<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChateryContact extends Model
{
    protected $fillable = ['chatery_contact_group_id', 'name', 'phone'];

    public function group()
    {
        return $this->belongsTo(ChateryContactGroup::class, 'chatery_contact_group_id');
    }
}
