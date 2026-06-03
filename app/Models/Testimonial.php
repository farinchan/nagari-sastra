<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getAvatar()
    {
        return $this->avatar ? Storage::url($this->avatar) : asset('back/media/svg/avatars/blank.svg');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
