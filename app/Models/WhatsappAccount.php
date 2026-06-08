<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class WhatsappAccount extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'access_token' => 'encrypted',
        'verify_token' => 'encrypted',
        'is_active' => 'boolean',
        'webhook_active' => 'boolean',
    ];

    public function chats()
    {
        return $this->hasMany(WhatsappChat::class);
    }

    public function messages()
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Send a request to Meta Graph API.
     */
    public function sendRequest($endpoint, $params = [], $method = 'POST')
    {
        try {
            $url = 'https://graph.facebook.com/v21.0/' . $endpoint;

            $response = $method === 'GET'
                ? Http::withToken($this->access_token)->get($url, $params)
                : Http::withToken($this->access_token)->post($url, $params);

            return $response->json();
        } catch (\Exception $e) {
            return ['error' => ['message' => $e->getMessage()]];
        }
    }

    /**
     * Send a text message via WhatsApp Cloud API.
     */
    public function sendMessage($to, $text)
    {
        return $this->sendRequest($this->phone_number_id . '/messages', [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $text],
        ]);
    }

    /**
     * Send a media message (image, document, video, audio).
     */
    public function sendMedia($to, $type, $mediaId, $caption = null)
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => $type,
            $type => ['id' => $mediaId],
        ];

        if ($caption && in_array($type, ['image', 'video', 'document'])) {
            $payload[$type]['caption'] = $caption;
        }

        return $this->sendRequest($this->phone_number_id . '/messages', $payload);
    }

    /**
     * Send a template message.
     */
    public function sendTemplate($to, $templateName, $languageCode = 'id')
    {
        return $this->sendRequest($this->phone_number_id . '/messages', [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => $languageCode],
            ],
        ]);
    }

    /**
     * Upload media to Meta and return media_id.
     */
    public function uploadMedia($filePath, $mimeType)
    {
        try {
            $url = 'https://graph.facebook.com/v21.0/' . $this->phone_number_id . '/media';

            $response = Http::withToken($this->access_token)
                ->attach('file', file_get_contents($filePath), basename($filePath), ['Content-Type' => $mimeType])
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'type' => $mimeType,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            return ['error' => ['message' => $e->getMessage()]];
        }
    }

    /**
     * Get media URL from Meta API, then download the binary content with auth header.
     */
    public function getMediaUrl($mediaId)
    {
        try {
            // Step 1: Get the media URL from Meta
            $meta = $this->sendRequest($mediaId, [], 'GET');
            $url = $meta['url'] ?? null;

            if (!$url) {
                return null;
            }

            // Step 2: Download the actual file with Bearer token
            $response = Http::withToken($this->access_token)->timeout(15)->get($url);

            if ($response->successful()) {
                return [
                    'body' => $response->body(),
                    'content_type' => $response->header('Content-Type') ?: 'application/octet-stream',
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
