<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChateryContactGroup extends Model
{
    protected $fillable = ['name', 'description'];

    public function contacts()
    {
        return $this->hasMany(ChateryContact::class);
    }
}
