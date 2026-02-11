<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardDataChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly string $reason = 'quote_updated'
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('dashboard.admin'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'reason' => $this->reason,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'data.changed';
    }
}
