<?php

namespace App\Livewire\CarModel;

use App\Models\CarModel;
use App\Models\User;
use App\Notifications\CarModelDeletedNotification;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class DeleteModal extends Component
{
    public bool $showStep1 = false;
    public bool $showStep2 = false;
    public bool $isDeleting = false;

    public ?int $carModelId = null;
    public ?string $carModelName = null;

    public string $deletionReason = '';
    public string $deletionDetail = '';

    public function mount(): void
    {
        $this->resetModal();
    }

    #[On('open-delete-car-model')]
    public function open(int $id): void
    {
        $this->resetModal();

        $model = CarModel::find($id);

        if (!$model) {
            Notification::make()
                ->title('ไม่พบข้อมูล')
                ->danger()
                ->send();
            return;
        }

        $this->carModelId = $model->id;
        $this->carModelName = "{$model->brand} {$model->name} ({$model->code})";
        $this->showStep1 = true;
    }

    public function proceedToConfirm(): void
    {
        $rules = [
            'deletionReason' => 'required',
        ];

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
        if ($this->isDeleting) {
            return;
        }

        $this->isDeleting = true;
        $modelName = $this->carModelName;
        $carModelId = $this->carModelId;
        $deletionReason = $this->deletionReason;
        $deletionDetail = $this->deletionDetail;

        // Reset modal ทันที (ปิด modal ก่อน)
        $this->resetModal();

        try {
            if (!$carModelId) {
                return;
            }

            $freshModel = CarModel::find($carModelId);

            if (!$freshModel) {
                Notification::make()
                    ->title('ไม่พบข้อมูล')
                    ->body('รุ่นรถนี้ถูกลบไปแล้ว')
                    ->danger()
                    ->send();

                // ใช้ redirect() แบบ Livewire (ไม่ต้อง return)
                $this->redirect(route('filament.admin.resources.car-models.index'));
                return;
            }

            DB::transaction(function () use ($freshModel, $deletionReason, $deletionDetail) {
                $freshModel->update([
                    'deletion_reason' => $deletionReason,
                    'deletion_detail' => $deletionDetail ?: null,
                ]);

                $user = auth()->user();
                $body = "{$user->name} ได้ลบรุ่นรถ {$freshModel->brand} {$freshModel->name} ({$freshModel->code})";
                $otherUsers = User::where('id', '!=', $user->id)->get();
                $notification = new CarModelDeletedNotification($freshModel, $user);

                foreach ($otherUsers as $u) {
                    $u->notify($notification);

                    Notification::make()
                        ->title('มีการลบรุ่นรถ')
                        ->body($body)
                        ->warning()
                        ->broadcast($u);
                }

                $freshModel->delete();
            });

            Notification::make()
                ->title('ลบรุ่นรถสำเร็จ')
                ->body("{$modelName} ถูกลบแล้ว")
                ->success()
                ->send();

            // ใช้ redirect() แบบ Livewire (ไม่ต้อง return)
            $this->redirect(route('filament.admin.resources.car-models.index'));

        } catch (\Exception $e) {
            Log::error('Delete CarModel Error: ' . $e->getMessage());

            Notification::make()
                ->title('เกิดข้อผิดพลาด')
                ->body('ไม่สามารถลบได้: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }



    public function cancel(): void
    {
        $this->resetModal();
    }

    public function backToStep1(): void
    {
        $this->showStep2 = false;
        $this->showStep1 = true;
    }

    public function resetModal(): void
    {
        $this->showStep1 = false;
        $this->showStep2 = false;
        $this->carModelId = null;
        $this->carModelName = null;
        $this->deletionReason = '';
        $this->deletionDetail = '';
        $this->isDeleting = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.car-model.delete-modal');
    }
}