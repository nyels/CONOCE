<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para tipos de vehículo iniciales
 * Basado en los tipos del sistema legacy
 */
class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'AUTO',
                'label' => 'Automóvil',
                'description' => 'Vehículo particular de pasajeros',
                'requires_cargo_description' => false,
                'risk_factor' => 1.00,
                'icon' => 'car',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'MOTO',
                'label' => 'Motocicleta',
                'description' => 'Vehículo de dos ruedas',
                'requires_cargo_description' => false,
                'risk_factor' => 1.25,
                'icon' => 'motorcycle',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'PICK UP',
                'label' => 'Pick Up',
                'description' => 'Camioneta tipo pick up',
                'requires_cargo_description' => true,
                'risk_factor' => 1.15,
                'icon' => 'truck-pickup',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'CAMION',
                'label' => 'Camión',
                'description' => 'Vehículo de carga pesada',
                'requires_cargo_description' => true,
                'risk_factor' => 1.30,
                'icon' => 'truck',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        $now = now();

        foreach ($types as $type) {
            DB::table('vehicle_types')->updateOrInsert(
                ['name' => $type['name']],
                array_merge($type, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
