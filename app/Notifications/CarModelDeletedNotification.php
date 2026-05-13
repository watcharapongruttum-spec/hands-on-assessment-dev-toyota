<?php

namespace App\Notifications;

use App\Models\CarModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CarModelDeletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CarModel $carModel,
        public User $deletedBy
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "{$this->deletedBy->name} ได้ลบรุ่นรถ {$this->carModel->brand} {$this->carModel->name} ({$this->carModel->code})",
            'brand'      => $this->carModel->brand,
            'name'       => $this->carModel->name,
            'code'       => $this->carModel->code,
            'deleted_by' => $this->deletedBy->name,
        ];
    }
}