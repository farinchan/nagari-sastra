<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCrmMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $source;      // 'webchat' or 'telegram'
    public string $senderName;
    public string $message;
    public string $url;
    public string $time;

    public function __construct(string $source, string $senderName, string $message, string $url)
    {
        $this->source = $source;
        $this->senderName = $senderName;
        $this->message = mb_substr($message, 0, 100);
        $this->url = $url;
        $this->time = now()->format('H:i');
    }

    /**
     * Broadcast on a private channel that only super-admin/marketing can listen.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('crm.notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new.message';
    }
}
