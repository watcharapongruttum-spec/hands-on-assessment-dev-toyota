<?php

namespace App\Filament\Resources\CarModels;

// use App\Filament\Resources\CarModels\CarModelResource\Pages;
use App\Filament\Resources\CarModels\Pages;
use App\Models\CarModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\EditAction;

class CarModelResource extends Resource
{
    protected static ?string $model = CarModel::class;
    protected static ?string $navigationLabel = 'รุ่นรถยนต์';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')
                ->label('รหัส')
                ->required()
                ->unique(ignoreRecord: true)
                ->regex('/^CM-\d{4}$/')
                ->placeholder('CM-0001'),
            TextInput::make('brand')
                ->label('แบรนด์')
                ->required(),
            TextInput::make('name')
                ->label('ชื่อรุ่น')
                ->required(),
            TextInput::make('year')
                ->label('ปี (ค.ศ.)')
                ->numeric()
                ->required(),
            Select::make('body_type')
                ->label('ประเภทรถ')
                ->options([
                    'Sedan' => 'Sedan',
                    'SUV' => 'SUV',
                    'Pickup' => 'Pickup',
                    'Hatchback' => 'Hatchback',
                    'Van' => 'Van',
                ])
                ->required(),
            TextInput::make('base_price')
                ->label('ราคาเริ่มต้น')
                ->numeric()
                ->minValue(0.01)
                ->required(),
            Toggle::make('is_active')
                ->label('ใช้งาน')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('รหัส')->sortable()->searchable(),
                TextColumn::make('brand')->label('แบรนด์')->sortable()->searchable(),
                TextColumn::make('name')->label('ชื่อรุ่น')->sortable()->searchable(),
                TextColumn::make('year')->label('ปี')->sortable(),
                TextColumn::make('body_type')->label('ประเภท')->sortable(),
                TextColumn::make('base_price')->label('ราคา')->money('THB')->sortable(),
                IconColumn::make('is_active')->label('ใช้งาน')->boolean(),
            ])
            ->filters([
                SelectFilter::make('brand')
                    ->label('แบรนด์')
                    ->options(fn() => CarModel::query()->pluck('brand', 'brand')->toArray()),
                SelectFilter::make('body_type')
                    ->label('ประเภทรถ')
                    ->options([
                        'Sedan' => 'Sedan',
                        'SUV' => 'SUV',
                        'Pickup' => 'Pickup',
                        'Hatchback' => 'Hatchback',
                        'Van' => 'Van',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteCarModelAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarModels::route('/'),
            'create' => Pages\CreateCarModel::route('/create'),
            'edit' => Pages\EditCarModel::route('/{record}/edit'),
        ];
    }
}