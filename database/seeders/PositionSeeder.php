<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Seedea los puestos iniciales del sistema
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'Director', 'description' => 'Director general de la empresa', 'sort_order' => 1],
            ['name' => 'Gerente', 'description' => 'Gerente de área', 'sort_order' => 2],
            ['name' => 'Administrativo', 'description' => 'Personal administrativo', 'sort_order' => 3],
            ['name' => 'Contabilidad', 'description' => 'Personal de contabilidad', 'sort_order' => 4],
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(
                ['name' => $position['name']],
                [
                    'description' => $position['description'],
                    'sort_order' => $position['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
