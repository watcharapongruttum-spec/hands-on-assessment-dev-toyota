<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CarModelChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public string $action;
    public int $carModelId;

    public function __construct(
        string $action,
        int $carModelId
    ) {
        $this->action = $action;
        $this->carModelId = $carModelId;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('car-models'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'car-model.changed';
    }
}