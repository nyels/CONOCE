<?php

namespace Database\Seeders;

use App\Models\MexicanState;
use Illuminate\Database\Seeder;

class MexicanStatesSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            'Aguascalientes',
            'Baja California',
            'Baja California Sur',
            'Campeche',
            'Chiapas',
            'Chihuahua',
            'Ciudad de Mexico',
            'Coahuila',
            'Colima',
            'Durango',
            'Estado de Mexico',
            'Guanajuato',
            'Guerrero',
            'Hidalgo',
            'Jalisco',
            'Michoacan',
            'Morelos',
            'Nayarit',
            'Nuevo Leon',
            'Oaxaca',
            'Puebla',
            'Queretaro',
            'Quintana Roo',
            'San Luis Potosi',
            'Sinaloa',
            'Sonora',
            'Tabasco',
            'Tamaulipas',
            'Tlaxcala',
            'Veracruz',
            'Yucatan',
            'Zacatecas',
        ];

        foreach ($states as $index => $name) {
            MexicanState::updateOrCreate(
                ['name' => $name],
                [
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
