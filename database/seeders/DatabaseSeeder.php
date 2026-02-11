<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Orden de ejecución:
     * 1. Roles y permisos (Spatie)
     * 2. Usuarios + Aseguradoras con config financiera
     * 3. Catálogos generales (tipos contacto, marcas, coberturas, deducibles, métodos pago)
     * 4. Tipos de vehículo (con campos extendidos: label, risk_factor, icon)
     * 5. Puestos del personal
     * 6. Estados mexicanos
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            InitialDataSeeder::class,
            CatalogSeeder::class,
            VehicleTypeSeeder::class,
            PositionSeeder::class,
            MexicanStatesSeeder::class,
        ]);
    }
}
