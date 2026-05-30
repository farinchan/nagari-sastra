<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class TelegramBot extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'token' => 'encrypted',
        'is_active' => 'boolean',
        'webhook_active' => 'boolean',
    ];

    public function chats()
    {
        return $this->hasMany(TelegramChat::class);
    }

    public function messages()
    {
        return $this->hasMany(TelegramMessage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getApiUrl()
    {
        return 'https://api.telegram.org/bot' . $this->token;
    }

    public function sendRequest($method, $params = [])
    {
        try {
            $response = Http::post($this->getApiUrl() . '/' . $method, $params);
            return $response->json();
        } catch (\Exception $e) {
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }
}
