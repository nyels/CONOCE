<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactType;
use App\Models\VehicleType;
use App\Models\VehicleBrand;
use App\Models\CoveragePackage;
use App\Models\DeductibleOption;
use App\Models\PaymentMethod;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
