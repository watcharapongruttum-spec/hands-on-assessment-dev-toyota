<?php

namespace App\Filament\Resources\CarModels;

use App\Events\CarModelDeleted;
use App\Models\User;
use App\Notifications\CarModelDeletedNotification;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class DeleteCarModelAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'delete')
            ->label('ลบ')
            ->color('danger')
            ->form([
                Select::make('deletion_reason')
                    ->label('เหตุผลการลบ')
                    ->options([
                        'ยกเลิกการผลิต'    => 'ยกเลิกการผลิต',
                        'ข้อมูลซ้ำ'        => 'ข้อมูลซ้ำ',
                        'ข้อมูลไม่ถูกต้อง' => 'ข้อมูลไม่ถูกต้อง',
                        'อื่นๆ'            => 'อื่นๆ',
                    ])
                    ->required()
                    ->live(),
                Textarea::make('deletion_detail')
                    ->label('รายละเอียดเพิ่มเติม')
                    ->visible(fn($get) => $get('deletion_reason') === 'อื่นๆ')
                    ->requiredIf('deletion_reason', 'อื่นๆ')
                    ->rows(3),
            ])
            ->modalHeading('ระบุเหตุผลการลบ')
            ->modalDescription(fn($record) => "รุ่น {$record->brand} {$record->name} ({$record->code})")
            ->modalSubmitActionLabel('ถัดไป →')
            ->requiresConfirmation()
            ->modalHeading(fn($record) => "ยืนยันการลบรุ่น {$record->brand} {$record->name} ({$record->code})")
            ->modalDescription('คุณต้องการลบรุ่นนี้ใช่หรือไม่?')
            ->modalSubmitActionLabel('ยืนยันการลบ')
            ->modalWidth('lg')
            ->action(function ($record, array $data) {
                $record->update([
                    'deletion_reason' => $data['deletion_reason'],
                    'deletion_detail' => $data['deletion_detail'] ?? null,
                ]);

                $user = auth()->user();
                $record->delete();

                // Broadcast real-time
                event(new CarModelDeleted($record, $user));

                // บันทึกลง database ให้ทุก user
                $notification = new CarModelDeletedNotification($record, $user);
                User::all()->each(fn($u) => $u->notify($notification));

                // แสดง toast ให้คนที่กำลัง online
                Notification::make()
                    ->title('ลบรุ่นรถสำเร็จ')
                    ->body("{$user->name} ได้ลบรุ่นรถ {$record->brand} {$record->name} ({$record->code})")
                    ->success()
                    ->send();
            });
    }
}