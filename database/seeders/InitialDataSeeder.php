<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Insurer;
use App\Models\InsurerFinancialSetting;
use Src\Domain\Shared\Enums\UserRole;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // Usuario Super Admin por defecto
        // ==========================================
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@cotizador.com'],
            [
                'name' => 'Super Administrador',
                'password' => Hash::make('password'),
                'role' => UserRole::SUPER_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole(UserRole::SUPER_ADMIN->value);

        // Usuario operador de prueba
        $operator = User::firstOrCreate(
            ['email' => 'operador@cotizador.com'],
            [
                'name' => 'Operador Demo',
                'password' => Hash::make('password'),
                'role' => UserRole::OPERATOR,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $operator->assignRole(UserRole::OPERATOR->value);

        $this->command->info('✅ Usuarios de prueba creados');

        // ==========================================
        // Aseguradoras principales de México
        // ==========================================
        $insurers = [
            [
                'name' => 'AXA Seguros',
                'short_name' => 'AXA',
                'code' => 'AXA',
                'primary_color' => '00008F',
                'policy_fee' => 450.00,
                'surcharge_semi' => 0.03,
                'surcharge_quarterly' => 0.06,
                'surcharge_monthly' => 0.10,
            ],
            [
                'name' => 'GNP Seguros',
                'short_name' => 'GNP',
                'code' => 'GNP',
                'primary_color' => 'FF6B00',
                'policy_fee' => 480.00,
                'surcharge_semi' => 0.025,
                'surcharge_quarterly' => 0.05,
                'surcharge_monthly' => 0.08,
            ],
            [
                'name' => 'Qualitas Compañía de Seguros',
                'short_name' => 'Qualitas',
                'code' => 'QUAL',
                'primary_color' => '1E3A5F',
                'policy_fee' => 400.00,
                'surcharge_semi' => 0.02,
                'surcharge_quarterly' => 0.04,
                'surcharge_monthly' => 0.07,
            ],
            [
                'name' => 'Chubb Seguros México',
                'short_name' => 'Chubb',
                'code' => 'CHUBB',
                'primary_color' => 'C41230',
                'policy_fee' => 520.00,
                'surcharge_semi' => 0.035,
                'surcharge_quarterly' => 0.07,
                'surcharge_monthly' => 0.12,
            ],
            [
                'name' => 'HDI Seguros',
                'short_name' => 'HDI',
                'code' => 'HDI',
                'primary_color' => '00A651',
                'policy_fee' => 380.00,
                'surcharge_semi' => 0.02,
                'surcharge_quarterly' => 0.045,
                'surcharge_monthly' => 0.075,
            ],
            [
                'name' => 'Mapfre México',
                'short_name' => 'Mapfre',
                'code' => 'MAPFRE',
                'primary_color' => 'DA291C',
                'policy_fee' => 460.00,
                'surcharge_semi' => 0.028,
                'surcharge_quarterly' => 0.055,
                'surcharge_monthly' => 0.09,
            ],
            [
                'name' => 'Zurich Seguros',
                'short_name' => 'Zurich',
                'code' => 'ZURICH',
                'primary_color' => '003399',
                'policy_fee' => 500.00,
                'surcharge_semi' => 0.03,
                'surcharge_quarterly' => 0.06,
                'surcharge_monthly' => 0.10,
            ],
            [
                'name' => 'Seguros Banorte',
                'short_name' => 'Banorte',
                'code' => 'BANORTE',
                'primary_color' => 'EC1C24',
                'policy_fee' => 420.00,
                'surcharge_semi' => 0.025,
                'surcharge_quarterly' => 0.05,
                'surcharge_monthly' => 0.085,
            ],
        ];

        foreach ($insurers as $index => $data) {
            $insurer = Insurer::firstOrCreate(
                ['code' => $data['code']],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'name' => $data['name'],
                    'short_name' => $data['short_name'],
                    'primary_color' => $data['primary_color'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );

            // Crear configuración financiera si no existe
            if (!$insurer->currentFinancialSetting) {
                InsurerFinancialSetting::create([
                    'insurer_id' => $insurer->id,
                    'policy_fee_cents' => (int) ($data['policy_fee'] * 100),
                    'surcharge_semiannual' => $data['surcharge_semi'],
                    'surcharge_quarterly' => $data['surcharge_quarterly'],
                    'surcharge_monthly' => $data['surcharge_monthly'],
                    'valid_from' => now()->startOfYear(),
                    'valid_until' => null,
                    'created_by' => $superAdmin->id,
                    'change_reason' => 'Configuración inicial del sistema',
                ]);
            }
        }

        $this->command->info('✅ ' . count($insurers) . ' aseguradoras creadas con configuración financiera');

        // ==========================================
        // Estados de México
        // ==========================================
        $states = [
            ['code' => 'AGS', 'name' => 'Aguascalientes'],
            ['code' => 'BC', 'name' => 'Baja California'],
            ['code' => 'BCS', 'name' => 'Baja California Sur'],
            ['code' => 'CAM', 'name' => 'Campeche'],
            ['code' => 'CHIS', 'name' => 'Chiapas'],
            ['code' => 'CHIH', 'name' => 'Chihuahua'],
            ['code' => 'CDMX', 'name' => 'Ciudad de México'],
            ['code' => 'COAH', 'name' => 'Coahuila'],
            ['code' => 'COL', 'name' => 'Colima'],
            ['code' => 'DGO', 'name' => 'Durango'],
            ['code' => 'GTO', 'name' => 'Guanajuato'],
            ['code' => 'GRO', 'name' => 'Guerrero'],
            ['code' => 'HGO', 'name' => 'Hidalgo'],
            ['code' => 'JAL', 'name' => 'Jalisco'],
            ['code' => 'MEX', 'name' => 'Estado de México'],
            ['code' => 'MICH', 'name' => 'Michoacán'],
            ['code' => 'MOR', 'name' => 'Morelos'],
            ['code' => 'NAY', 'name' => 'Nayarit'],
            ['code' => 'NL', 'name' => 'Nuevo León'],
            ['code' => 'OAX', 'name' => 'Oaxaca'],
            ['code' => 'PUE', 'name' => 'Puebla'],
            ['code' => 'QRO', 'name' => 'Querétaro'],
            ['code' => 'QROO', 'name' => 'Quintana Roo'],
            ['code' => 'SLP', 'name' => 'San Luis Potosí'],
            ['code' => 'SIN', 'name' => 'Sinaloa'],
            ['code' => 'SON', 'name' => 'Sonora'],
            ['code' => 'TAB', 'name' => 'Tabasco'],
            ['code' => 'TAMS', 'name' => 'Tamaulipas'],
            ['code' => 'TLAX', 'name' => 'Tlaxcala'],
            ['code' => 'VER', 'name' => 'Veracruz'],
            ['code' => 'YUC', 'name' => 'Yucatán'],
            ['code' => 'ZAC', 'name' => 'Zacatecas'],
        ];

        foreach ($states as $state) {
            \Illuminate\Support\Facades\DB::table('states')->insertOrIgnore($state);
        }

        $this->command->info('✅ 32 estados de México creados');
    }
}
