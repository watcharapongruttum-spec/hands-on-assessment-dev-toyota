<?php

namespace App\Livewire\CarModel;

use App\Events\CarModelDeleted;
use App\Models\CarModel;
use App\Models\User;
use App\Notifications\CarModelDeletedNotification;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\Component;

class DeleteModal extends Component
{
    public bool $showStep1 = false;
    public bool $showStep2 = false;
    public ?CarModel $carModel = null;
    public string $deletionReason = '';
    public string $deletionDetail = '';

    #[On('open-delete-car-model')]
    public function open(int $id): void
    {
        $this->carModel = CarModel::findOrFail($id);
        $this->deletionReason = '';
        $this->deletionDetail = '';
        $this->showStep1 = true;
        $this->showStep2 = false;
    }

    public function proceedToConfirm(): void
    {
        $rules = ['deletionReason' => 'required'];
        if ($this->deletionReason === 'อื่นๆ') {
            $rules['deletionDetail'] = 'required';
        }
        $this->validate($rules, [
            'deletionReason.required' => 'กรุณาเลือกเหตุผลการลบ',
            'deletionDetail.required' => 'กรุณาระบุรายละเอียด',
        ]);

        $this->showStep1 = false;
        $this->showStep2 = true;
    }



    public function confirmDelete(): void
    {
        $this->carModel->update([
            'deletion_reason' => $this->deletionReason,
            'deletion_detail' => $this->deletionDetail ?: null,
        ]);

        $user = auth()->user();
        $this->carModel->delete();

        event(new CarModelDeleted($this->carModel, $user));

        $body = "{$user->name} ได้ลบรุ่นรถ {$this->carModel->brand} {$this->carModel->name} ({$this->carModel->code})";

        $allUsers = User::all();

        // บันทึก DB ทุก user
        $notification = new CarModelDeletedNotification($this->carModel, $user);
        $allUsers->each(fn($u) => $u->notify($notification));

        // Broadcast Filament notification ไปทุก user ที่ online (ยกเว้นคนที่กดลบ)
        $broadcastNotif = Notification::make()
            ->title('มีการลบรุ่นรถ')
            ->body($body)
            ->warning();

        $allUsers->each(function ($u) use ($broadcastNotif, $user) {
            if ($u->id !== $user->id) {
                $broadcastNotif->broadcast($u);
            }
        });

        // Flash ให้คนที่กดลบ
        Notification::make()
            ->title('ลบรุ่นรถสำเร็จ')
            ->body($body)
            ->success()
            ->send();

        $this->showStep2 = false;
        $this->carModel = null;
        $this->js('window.location.reload()');
    }












    public function cancel(): void
    {
        $this->showStep1 = false;
        $this->showStep2 = false;
    }

    public function backToStep1(): void
    {
        $this->showStep2 = false;
        $this->showStep1 = true;
    }

    public function render()
    {
        return view('livewire.car-model.delete-modal');
    }
}