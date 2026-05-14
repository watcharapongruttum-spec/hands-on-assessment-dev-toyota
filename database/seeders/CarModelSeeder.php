<?php

namespace Database\Seeders;

use App\Models\CarModel;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Toyota' => [
                ['Corolla Altis', 'Sedan', 1100000],
                ['Yaris Ativ', 'Sedan', 650000],
                ['Hilux Revo', 'Pickup', 950000],
                ['Camry', 'Sedan', 1450000],
                ['Fortuner', 'SUV', 1700000],
                ['Veloz', 'SUV', 890000],
                ['Avanza', 'MPV', 820000],
                ['Supra', 'Coupe', 5200000],
            ],

            'Honda' => [
                ['City', 'Sedan', 780000],
                ['Civic', 'Sedan', 1200000],
                ['HR-V', 'SUV', 1150000],
                ['CR-V', 'SUV', 1600000],
                ['BR-V', 'SUV', 950000],
                ['Jazz', 'Hatchback', 720000],
                ['Accord', 'Sedan', 1800000],
            ],

            'Ford' => [
                ['Everest', 'SUV', 1750000],
                ['Ranger', 'Pickup', 1050000],
                ['Mustang', 'Coupe', 3600000],
                ['Territory', 'SUV', 1250000],
            ],

            'Nissan' => [
                ['Almera', 'Sedan', 620000],
                ['Navara', 'Pickup', 980000],
                ['Terra', 'SUV', 1450000],
                ['Kicks', 'SUV', 890000],
                ['Sylphy', 'Sedan', 950000],
            ],

            'Mazda' => [
                ['Mazda2', 'Hatchback', 680000],
                ['Mazda3', 'Sedan', 980000],
                ['CX-3', 'SUV', 890000],
                ['CX-5', 'SUV', 1350000],
                ['CX-8', 'SUV', 1750000],
            ],

            'Mitsubishi' => [
                ['Attrage', 'Sedan', 590000],
                ['Xpander', 'MPV', 890000],
                ['Pajero Sport', 'SUV', 1550000],
                ['Triton', 'Pickup', 890000],
            ],

            'Isuzu' => [
                ['D-Max', 'Pickup', 920000],
                ['MU-X', 'SUV', 1500000],
            ],

            'BMW' => [
                ['320i', 'Sedan', 2650000],
                ['330e', 'Sedan', 2990000],
                ['X1', 'SUV', 2400000],
                ['X5', 'SUV', 4900000],
            ],

            'Mercedes-Benz' => [
                ['C-Class', 'Sedan', 2750000],
                ['E-Class', 'Sedan', 3500000],
                ['GLA', 'SUV', 2600000],
                ['GLC', 'SUV', 3600000],
            ],

            'Audi' => [
                ['A4', 'Sedan', 2900000],
                ['A6', 'Sedan', 4200000],
                ['Q5', 'SUV', 3700000],
                ['Q7', 'SUV', 5200000],
            ],

            'Hyundai' => [
                ['Creta', 'SUV', 950000],
                ['Staria', 'Van', 1800000],
                ['Elantra', 'Sedan', 1200000],
                ['Tucson', 'SUV', 1650000],
            ],

            'Kia' => [
                ['Carnival', 'Van', 2200000],
                ['Seltos', 'SUV', 990000],
                ['Sorento', 'SUV', 1890000],
                ['K3', 'Sedan', 850000],
            ],

            'Subaru' => [
                ['Forester', 'SUV', 1450000],
                ['XV', 'SUV', 1290000],
                ['WRX', 'Sedan', 2790000],
            ],

            'Chevrolet' => [
                ['Captiva', 'SUV', 999000],
                ['Colorado', 'Pickup', 1050000],
                ['Trailblazer', 'SUV', 1350000],
            ],

            'Suzuki' => [
                ['Swift', 'Hatchback', 650000],
                ['Ciaz', 'Sedan', 675000],
                ['Ertiga', 'MPV', 790000],
                ['XL7', 'SUV', 820000],
            ],
        ];


        $allCars = [];
        foreach ($brands as $brand => $models) {
            foreach ($models as $model) {
                for ($year = 2020; $year <= 2024; $year++) {
                    $priceIncrease = ($year - 2020) * 20000; 
                    $allCars[] = [
                        'brand'      => $brand,
                        'name'       => $model[0],
                        'year'       => $year,
                        'body_type'  => $model[1],
                        'base_price' => $model[2] + $priceIncrease,
                        'is_active'  => true,
                    ];
                }
            }
        }


        $carsToInsert = array_slice($allCars, 0, 100);


        foreach ($carsToInsert as $index => $car) {
            CarModel::create([
                'code'       => 'CM-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'brand'      => $car['brand'],
                'name'       => $car['name'],
                'year'       => $car['year'],
                'body_type'  => $car['body_type'],
                'base_price' => $car['base_price'],
                'is_active'  => $car['is_active'],
            ]);
        }
    }
}