<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailGroup extends Model
{
    protected $guarded = ['id'];

    public function contacts()
    {
        return $this->hasMany(EmailContact::class);
    }

    public function campaigns()
    {
        return $this->hasMany(EmailCampaign::class);
    }
}
