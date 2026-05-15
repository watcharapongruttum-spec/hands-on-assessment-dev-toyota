<?php

namespace App\Filament\Resources\CarModels\Pages;

use App\Filament\Resources\CarModels\CarModelResource;
use App\Filament\Resources\CarModels\DeleteCarModelAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCarModel extends EditRecord
{
    protected static string $resource = CarModelResource::class;

    protected function getHeaderActions(): array
    {
        return [

            DeleteCarModelAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}