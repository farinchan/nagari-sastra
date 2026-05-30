<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailContact extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_subscribed' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(EmailGroup::class, 'email_group_id');
    }

    public function scopeSubscribed($query)
    {
        return $query->where('is_subscribed', true);
    }
}
