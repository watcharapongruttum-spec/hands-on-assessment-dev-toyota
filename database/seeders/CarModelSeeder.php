<?php

namespace Database\Seeders;

use App\Models\CarModel;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [

            // Toyota
            [
                'code' => 'CM-0005',
                'brand' => 'Toyota',
                'name' => 'Corolla Altis',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 1100000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0006',
                'brand' => 'Toyota',
                'name' => 'Yaris Ativ',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 650000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0007',
                'brand' => 'Toyota',
                'name' => 'Hilux Revo',
                'year' => 2024,
                'body_type' => 'Pickup',
                'base_price' => 950000,
                'is_active' => true,
            ],

            // Honda
            [
                'code' => 'CM-0008',
                'brand' => 'Honda',
                'name' => 'City',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 780000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0009',
                'brand' => 'Honda',
                'name' => 'HR-V',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 1150000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0010',
                'brand' => 'Honda',
                'name' => 'CR-V',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 1600000,
                'is_active' => true,
            ],

            // Ford
            [
                'code' => 'CM-0011',
                'brand' => 'Ford',
                'name' => 'Everest',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 1750000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0012',
                'brand' => 'Ford',
                'name' => 'Mustang',
                'year' => 2024,
                'body_type' => 'Coupe',
                'base_price' => 3600000,
                'is_active' => true,
            ],

            // Nissan
            [
                'code' => 'CM-0013',
                'brand' => 'Nissan',
                'name' => 'Almera',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 620000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0014',
                'brand' => 'Nissan',
                'name' => 'Navara',
                'year' => 2024,
                'body_type' => 'Pickup',
                'base_price' => 980000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0015',
                'brand' => 'Nissan',
                'name' => 'Terra',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 1450000,
                'is_active' => true,
            ],

            // Mazda
            [
                'code' => 'CM-0016',
                'brand' => 'Mazda',
                'name' => 'Mazda2',
                'year' => 2024,
                'body_type' => 'Hatchback',
                'base_price' => 680000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0017',
                'brand' => 'Mazda',
                'name' => 'Mazda3',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 980000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0018',
                'brand' => 'Mazda',
                'name' => 'CX-5',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 1350000,
                'is_active' => true,
            ],

            // Mitsubishi
            [
                'code' => 'CM-0019',
                'brand' => 'Mitsubishi',
                'name' => 'Attrage',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 590000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0020',
                'brand' => 'Mitsubishi',
                'name' => 'Pajero Sport',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 1550000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0021',
                'brand' => 'Mitsubishi',
                'name' => 'Triton',
                'year' => 2024,
                'body_type' => 'Pickup',
                'base_price' => 890000,
                'is_active' => true,
            ],

            // Isuzu
            [
                'code' => 'CM-0022',
                'brand' => 'Isuzu',
                'name' => 'D-Max',
                'year' => 2024,
                'body_type' => 'Pickup',
                'base_price' => 920000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0023',
                'brand' => 'Isuzu',
                'name' => 'MU-X',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 1500000,
                'is_active' => true,
            ],

            // BMW
            [
                'code' => 'CM-0024',
                'brand' => 'BMW',
                'name' => '320i',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 2650000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0025',
                'brand' => 'BMW',
                'name' => 'X5',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 4900000,
                'is_active' => true,
            ],

            // Mercedes-Benz
            [
                'code' => 'CM-0026',
                'brand' => 'Mercedes-Benz',
                'name' => 'C-Class',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 2750000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0027',
                'brand' => 'Mercedes-Benz',
                'name' => 'GLC',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 3600000,
                'is_active' => true,
            ],

            // Audi
            [
                'code' => 'CM-0028',
                'brand' => 'Audi',
                'name' => 'A4',
                'year' => 2024,
                'body_type' => 'Sedan',
                'base_price' => 2900000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0029',
                'brand' => 'Audi',
                'name' => 'Q7',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 5200000,
                'is_active' => true,
            ],

            // Hyundai
            [
                'code' => 'CM-0030',
                'brand' => 'Hyundai',
                'name' => 'Creta',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 950000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0031',
                'brand' => 'Hyundai',
                'name' => 'Staria',
                'year' => 2024,
                'body_type' => 'Van',
                'base_price' => 1800000,
                'is_active' => true,
            ],

            // Kia
            [
                'code' => 'CM-0032',
                'brand' => 'Kia',
                'name' => 'Carnival',
                'year' => 2024,
                'body_type' => 'Van',
                'base_price' => 2200000,
                'is_active' => true,
            ],
            [
                'code' => 'CM-0033',
                'brand' => 'Kia',
                'name' => 'Seltos',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 990000,
                'is_active' => true,
            ],

            // Subaru
            [
                'code' => 'CM-0034',
                'brand' => 'Subaru',
                'name' => 'Forester',
                'year' => 2024,
                'body_type' => 'SUV',
                'base_price' => 1450000,
                'is_active' => true,
            ],
        ];

        foreach ($cars as $car) {
            CarModel::create($car);
        }
    }
}