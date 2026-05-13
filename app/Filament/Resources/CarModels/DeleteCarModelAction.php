<?php

namespace App\Filament\Resources\CarModels;

use App\Models\CarModel;
use Filament\Actions\Action;

class DeleteCarModelAction
{
    public static function make(): Action
    {
        return Action::make('delete')
            ->label('ลบ')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->button()
            ->extraAttributes(fn(CarModel $record): array => [
                'x-data' => '{}',
                'x-on:click.prevent.stop' => "Livewire.dispatch('open-delete-car-model', {id: {$record->id}})",
            ]);
    }
}