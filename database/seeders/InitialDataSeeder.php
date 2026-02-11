<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Insurer;
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
        // Aseguradoras (sin config financiera, se configura desde admin)
        // ==========================================
        $insurers = [
            ['name' => 'Allianz',  'short_name' => 'Allianz',  'code' => 'ALLIANZ', 'logo_path' => 'images/allianz.jpeg',       'primary_color' => '003781'],
            ['name' => 'ANA',      'short_name' => 'ANA',      'code' => 'ANA',     'logo_path' => 'images/ana.jpeg',           'primary_color' => '00529B'],
            ['name' => 'Atlas',    'short_name' => 'Atlas',    'code' => 'ATLAS',   'logo_path' => 'images/atlas.jpeg',         'primary_color' => '004B8D'],
            ['name' => 'Banorte',  'short_name' => 'Banorte',  'code' => 'BANORTE', 'logo_path' => 'images/banorte.jpeg',       'primary_color' => 'EC1C24'],
            ['name' => 'Berkley',  'short_name' => 'Berkley',  'code' => 'BERKLEY', 'logo_path' => 'images/berkley.jpeg',       'primary_color' => '00853E'],
            ['name' => 'BX+',      'short_name' => 'BX+',      'code' => 'BXP',     'logo_path' => 'images/bx+.jpeg',          'primary_color' => '005F9E'],
            ['name' => 'Chubb',    'short_name' => 'Chubb',    'code' => 'CHUBB',   'logo_path' => 'images/chubb.jpeg',         'primary_color' => 'C41230'],
            ['name' => 'GMX',      'short_name' => 'GMX',      'code' => 'GMX',     'logo_path' => 'images/gmx.jpeg',           'primary_color' => '67CFE0'],
            ['name' => 'GNP',      'short_name' => 'GNP',      'code' => 'GNP',     'logo_path' => 'images/gnp.jpeg',           'primary_color' => 'FF6B00'],
            ['name' => 'HDI',      'short_name' => 'HDI',      'code' => 'HDI',     'logo_path' => 'images/hdi seguros.jpeg',   'primary_color' => '00A651'],
            ['name' => 'Mapfre',   'short_name' => 'Mapfre',   'code' => 'MAPFRE',  'logo_path' => 'images/mapfre.jpeg',        'primary_color' => 'DA291C'],
            ['name' => 'Qualitas', 'short_name' => 'Qualitas', 'code' => 'QUAL',    'logo_path' => 'images/qualitas.jpeg',      'primary_color' => '1E3A5F'],
            ['name' => 'Zurich',   'short_name' => 'Zurich',   'code' => 'ZURICH',  'logo_path' => 'images/zurich.jpeg',        'primary_color' => '003399'],
        ];

        foreach ($insurers as $index => $data) {
            Insurer::updateOrCreate(
                ['code' => $data['code']],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'name' => $data['name'],
                    'short_name' => $data['short_name'],
                    'logo_path' => $data['logo_path'],
                    'primary_color' => $data['primary_color'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $this->command->info('✅ ' . count($insurers) . ' aseguradoras creadas (config financiera se establece desde admin)');
    }
}
