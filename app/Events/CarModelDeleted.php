<?php

namespace App\Events;

use App\Models\CarModel;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CarModelDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public CarModel $carModel,
        public User $deletedBy
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('car-models'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'car-model.deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'brand' => $this->carModel->brand,
            'name' => $this->carModel->name,
            'code' => $this->carModel->code,
            'deleted_by' => $this->deletedBy->name,
        ];
    }
}