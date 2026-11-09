<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ExpenseAdded implements ShouldBroadcastNow
{
    public function __construct(public int $userId) {}

    public function broadcastOn(): Channel
    {
        return new Channel('expenses');
    }

    public function broadcastAs(): string
    {
        return 'expense-added';
    }

    public function broadcastWith(): array
    {
        return ['user_id' => $this->userId];
    }
}
