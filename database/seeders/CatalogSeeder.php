<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactType;
use App\Models\VehicleType;
use App\Models\VehicleBrand;
use App\Models\CoveragePackage;
use App\Models\DeductibleOption;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            [
                'email' => 'admin@cotizador.com'
            ],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Contact Types
        $contactTypes = [
            ['name' => 'Agente', 'sort_order' => 1],
            ['name' => 'Subagente', 'sort_order' => 2],
            ['name' => 'Cliente Directo', 'sort_order' => 3],
            ['name' => 'Referido', 'sort_order' => 4],
            ['name' => 'Empleado', 'sort_order' => 5],
        ];

        foreach ($contactTypes as $type) {
            ContactType::firstOrCreate(['name' => $type['name']], $type);
        }

        // Vehicle Types
        $vehicleTypes = [
            ['name' => 'Auto', 'sort_order' => 1],
            ['name' => 'SUV', 'sort_order' => 2],
            ['name' => 'Pick Up', 'sort_order' => 3],
            ['name' => 'Moto', 'sort_order' => 4],
            ['name' => 'Camión', 'sort_order' => 5],
            ['name' => 'Van', 'sort_order' => 6],
        ];

        foreach ($vehicleTypes as $type) {
            VehicleType::firstOrCreate(['name' => $type['name']], $type);
        }

        // Vehicle Brands
        $brands = [
            'Toyota',
            'Honda',
            'Nissan',
            'Mazda',
            'Volkswagen',
            'Ford',
            'Chevrolet',
            'KIA',
            'Hyundai',
            'Audi',
            'BMW',
            'Mercedes-Benz',
            'SEAT',
            'Peugeot',
            'Renault',
            'Mitsubishi',
            'Suzuki',
            'Jeep',
            'Dodge',
            'RAM'
        ];

        foreach ($brands as $i => $brand) {
            VehicleBrand::firstOrCreate(['name' => $brand], [
                'name' => $brand,
                'is_active' => true,
            ]);
        }

        // Insurers
        $insurers = [
            // Root images
            ['name' => 'Qualitas', 'code' => 'QUA', 'primary_color' => '#f29100', 'logo_path' => 'qualitas.png'],
            ['name' => 'GNP Seguros', 'code' => 'GNP', 'primary_color' => '#f29100', 'logo_path' => 'gnp.png'],
            ['name' => 'Chubb', 'code' => 'CHU', 'primary_color' => '#000000', 'logo_path' => 'chubb.png'],
            ['name' => 'AXA', 'code' => 'AXA', 'primary_color' => '#00008f', 'logo_path' => 'axa.png'],
            ['name' => 'HDI Seguros', 'code' => 'HDI', 'primary_color' => '#009640', 'logo_path' => 'hdi.png'],
            ['name' => 'Seguros Banorte', 'code' => 'BAN', 'primary_color' => '#EB0029', 'logo_path' => 'banorte.png'],
            ['name' => 'Bx+', 'code' => 'BXP', 'primary_color' => '#005F9E', 'logo_path' => 'bx.png'],

            // Images in public/images
            ['name' => 'Allianz', 'code' => 'ALL', 'primary_color' => '#003781', 'logo_path' => 'images/allianz.jpeg'],
            ['name' => 'ANA Seguros', 'code' => 'ANA', 'primary_color' => '#00529B', 'logo_path' => 'images/ana.jpeg'],
            ['name' => 'Atlas', 'code' => 'ATL', 'primary_color' => '#004B8D', 'logo_path' => 'images/atlas.jpeg'],
            ['name' => 'Berkley', 'code' => 'BER', 'primary_color' => '#00853E', 'logo_path' => 'images/berkley.jpeg'],
            ['name' => 'GMX Seguros', 'code' => 'GMX', 'primary_color' => '#67CFE0', 'logo_path' => 'images/gmx.jpeg'],
            ['name' => 'Mapfre', 'code' => 'MAP', 'primary_color' => '#D81E05', 'logo_path' => 'images/mapfre.jpeg'],
            ['name' => 'MetLife', 'code' => 'MET', 'primary_color' => '#0090DA', 'logo_path' => 'images/metlife.jpeg'],
            ['name' => 'Zurich', 'code' => 'ZUR', 'primary_color' => '#23366F', 'logo_path' => 'images/zurich.jpeg'],
        ];

        foreach ($insurers as $ins) {
            \App\Models\Insurer::updateOrCreate(['code' => $ins['code']], [
                'name' => $ins['name'],
                'primary_color' => $ins['primary_color'],
                'logo_path' => $ins['logo_path'],
                'is_active' => true,
                'sort_order' => \App\Models\Insurer::max('sort_order') + 1,
            ]);
        }

        // Coverage Packages
        $packages = [
            ['name' => 'Amplio', 'code' => 'AMP', 'description' => 'Cobertura completa con todos los riesgos', 'sort_order' => 1],
            ['name' => 'Amplio Plus', 'code' => 'AMP+', 'description' => 'Cobertura amplia con beneficios adicionales', 'sort_order' => 2],
            ['name' => 'Limitado', 'code' => 'LIM', 'description' => 'Cobertura con límites específicos', 'sort_order' => 3],
            ['name' => 'Responsabilidad Civil', 'code' => 'RC', 'description' => 'Solo cobertura de responsabilidad civil', 'sort_order' => 4],
        ];

        foreach ($packages as $pkg) {
            CoveragePackage::firstOrCreate(['code' => $pkg['code']], $pkg);
        }

        // Deductible Options
        $deductibles = [
            ['name' => '3%', 'percentage' => 3.00, 'sort_order' => 1],
            ['name' => '5%', 'percentage' => 5.00, 'sort_order' => 2],
            ['name' => '10%', 'percentage' => 10.00, 'sort_order' => 3],
            ['name' => '15%', 'percentage' => 15.00, 'sort_order' => 4],
            ['name' => '20%', 'percentage' => 20.00, 'sort_order' => 5],
        ];

        foreach ($deductibles as $ded) {
            DeductibleOption::firstOrCreate(['percentage' => $ded['percentage']], $ded);
        }

        // Payment Methods
        $paymentMethods = [
            ['name' => 'Anual', 'code' => 'ANU', 'installments' => 1, 'surcharge_percentage' => 0, 'sort_order' => 1],
            ['name' => 'Semestral', 'code' => 'SEM', 'installments' => 2, 'surcharge_percentage' => 5.00, 'sort_order' => 2],
            ['name' => 'Trimestral', 'code' => 'TRI', 'installments' => 4, 'surcharge_percentage' => 8.00, 'sort_order' => 3],
            ['name' => 'Mensual', 'code' => 'MEN', 'installments' => 12, 'surcharge_percentage' => 12.00, 'sort_order' => 4],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate(['code' => $method['code']], $method);
        }

        $this->command->info('✅ Catálogos sembrados correctamente');
    }
}
