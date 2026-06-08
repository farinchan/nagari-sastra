<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ChaterySession extends Model
{
    use LogsActivity;

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded()
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    protected $guarded = ['id'];

    protected $casts = [
        'api_key' => 'encrypted',
        'is_active' => 'boolean',
        'is_connected' => 'boolean',
        'is_default' => 'boolean',
    ];


    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeConnected($query)
    {
        return $query->where('is_connected', true);
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /**
     * Get full API URL for a given endpoint.
     */
    public function getApiUrl($endpoint): string
    {
        return rtrim($this->api_url, '/') . $endpoint;
    }

    /**
     * Get API headers for authentication.
     */
    public function apiHeaders(): array
    {
        $headers = [];
        if ($this->api_key) {
            $headers['X-Api-Key'] = $this->api_key;
        }
        return $headers;
    }

    /**
     * Get the default active session.
     */
    public static function getDefault(): ?self
    {
        return self::where('is_default', true)->where('is_active', true)->first();
    }

    // ==========================================
    // API METHODS
    // ==========================================

    /**
     * Send a text message.
     */
    public function sendMessage($to, $text)
    {
        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->post($this->getApiUrl('/send-message'), [
                    'session' => $this->session_id,
                    'to' => $to,
                    'text' => $text,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::sendMessage error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send an image message.
     */
    public function sendImage($to, $imageUrl, $caption = null)
    {
        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->post($this->getApiUrl('/send-image'), [
                    'session' => $this->session_id,
                    'to' => $to,
                    'urlImage' => $imageUrl,
                    'caption' => $caption,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::sendImage error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send bulk messages.
     */
    public function sendBulkMessage($data, $delay = 1000)
    {
        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->post($this->getApiUrl('/send-bulk-message'), [
                    'session' => $this->session_id,
                    'delay' => $delay,
                    'data' => $data,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::sendBulkMessage error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Check session status.
     */
    public function checkStatus()
    {
        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->get($this->getApiUrl('/api/whatsapp/sessions/' . $this->session_id . '/status'));

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::checkStatus error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Connect/create session (returns QR).
     */
    public function connectSession()
    {
        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->post($this->getApiUrl('/api/whatsapp/sessions/' . $this->session_id . '/connect'));

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::connectSession error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Disconnect/delete session.
     */
    public function disconnectSession()
    {
        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->delete($this->getApiUrl('/api/whatsapp/sessions/' . $this->session_id));

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::disconnectSession error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get QR code image URL.
     */
    public function getQrImageUrl(): string
    {
        return rtrim($this->api_url, '/') . '/api/whatsapp/sessions/' . $this->session_id . '/qr/image';
    }

    // ==========================================
    // CHAT HISTORY (via Chatery API)
    // ==========================================

    /**
     * Get chats overview from Chatery API.
     */
    public function getChats($limit = 50, $offset = 0)
    {
        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->post($this->getApiUrl('/api/whatsapp/chats/overview'), [
                    'sessionId' => $this->session_id,
                    'limit' => $limit,
                    'offset' => $offset,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::getChats error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get messages for a specific chat from Chatery API.
     */
    public function getChatMessages($chatId, $limit = 50, $cursor = null)
    {
        try {
            $params = [
                'sessionId' => $this->session_id,
                'chatId' => $chatId,
                'limit' => $limit,
            ];
            if ($cursor) {
                $params['cursor'] = $cursor;
            }

            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->post($this->getApiUrl('/api/whatsapp/chats/messages'), $params);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::getChatMessages error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send text message via Chatery chats endpoint.
     */
    public function sendText($chatId, $message)
    {
        try {
            $response = Http::withHeaders($this->apiHeaders())
                ->timeout(30)
                ->post($this->getApiUrl('/api/whatsapp/chats/send-text'), [
                    'sessionId' => $this->session_id,
                    'chatId' => $chatId,
                    'message' => $message,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ChaterySession::sendText error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
