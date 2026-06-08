<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TelegramBot extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'token' => 'encrypted',
        'is_active' => 'boolean',
        'webhook_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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

    /**
     * Send a multipart request (for file uploads like sendPhoto, sendDocument).
     */
    public function sendMultipart($method, $params = [], $file = null, $fileField = 'photo')
    {
        try {
            $request = Http::asMultipart();

            foreach ($params as $key => $value) {
                $request = $request->attach($key, $value);
            }

            if ($file) {
                $request = $request->attach(
                    $fileField,
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }

            $response = $request->post($this->getApiUrl() . '/' . $method);
            return $response->json();
        } catch (\Exception $e) {
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }
}
