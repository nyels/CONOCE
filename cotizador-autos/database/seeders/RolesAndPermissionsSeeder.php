<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Src\Domain\Shared\Enums\UserRole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================================
        // Crear Permisos
        // ==========================================

        // Módulo: Cotizaciones
        $quotePermissions = [
            'quotes.view' => 'Ver cotizaciones',
            'quotes.view-all' => 'Ver todas las cotizaciones',
            'quotes.create' => 'Crear cotizaciones',
            'quotes.edit' => 'Editar cotizaciones',
            'quotes.delete' => 'Eliminar cotizaciones',
            'quotes.send' => 'Enviar cotizaciones',
            'quotes.conclude' => 'Concretar cotizaciones',
            'quotes.reject' => 'Rechazar cotizaciones',
            'quotes.annul' => 'Anular cotizaciones',
            'quotes.export' => 'Exportar cotizaciones',
        ];

        // Módulo: Clientes
        $customerPermissions = [
            'customers.view' => 'Ver clientes',
            'customers.create' => 'Crear clientes',
            'customers.edit' => 'Editar clientes',
            'customers.delete' => 'Eliminar clientes',
            'customers.export' => 'Exportar clientes',
        ];

        // Módulo: Contactos (Intermediarios)
        $contactPermissions = [
            'contacts.view' => 'Ver contactos',
            'contacts.create' => 'Crear contactos',
            'contacts.edit' => 'Editar contactos',
            'contacts.delete' => 'Eliminar contactos',
        ];

        // Módulo: Aseguradoras
        $insurerPermissions = [
            'insurers.view' => 'Ver aseguradoras',
            'insurers.create' => 'Crear aseguradoras',
            'insurers.edit' => 'Editar aseguradoras',
            'insurers.delete' => 'Eliminar aseguradoras',
            'insurers.configure' => 'Configurar aseguradoras',
        ];

        // Módulo: Usuarios
        $userPermissions = [
            'users.view' => 'Ver usuarios',
            'users.create' => 'Crear usuarios',
            'users.edit' => 'Editar usuarios',
            'users.delete' => 'Eliminar usuarios',
            'users.assign-roles' => 'Asignar roles',
        ];

        // Módulo: Reportes
        $reportPermissions = [
            'reports.view' => 'Ver reportes',
            'reports.export' => 'Exportar reportes',
            'reports.schedule' => 'Programar reportes',
        ];

        // Módulo: Configuración
        $settingPermissions = [
            'settings.view' => 'Ver configuración',
            'settings.update' => 'Actualizar configuración',
            'settings.audit' => 'Ver auditoría',
        ];

        // Crear todos los permisos
        $allPermissions = array_merge(
            $quotePermissions,
            $customerPermissions,
            $contactPermissions,
            $insurerPermissions,
            $userPermissions,
            $reportPermissions,
            $settingPermissions
        );

        foreach ($allPermissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['guard_name' => 'web']
            );
        }

        // ==========================================
        // Crear Roles con Permisos
        // ==========================================

        // Super Admin - Todo
        $superAdmin = Role::firstOrCreate(['name' => UserRole::SUPER_ADMIN->value]);
        $superAdmin->syncPermissions(array_keys($allPermissions));

        // Admin - Todo excepto auditoría de sistema
        $admin = Role::firstOrCreate(['name' => UserRole::ADMIN->value]);
        $adminPermissions = array_keys($allPermissions);
        $admin->syncPermissions(array_filter($adminPermissions, fn($p) => $p !== 'settings.audit'));

        // Manager - Supervisión y reportes, sin gestión de usuarios ni config
        $manager = Role::firstOrCreate(['name' => UserRole::MANAGER->value]);
        $manager->syncPermissions([
            'quotes.view',
            'quotes.view-all',
            'quotes.export',
            'customers.view',
            'customers.export',
            'contacts.view',
            'insurers.view',
            'reports.view',
            'reports.export',
        ]);

        // Operator - Operaciones propias
        $operator = Role::firstOrCreate(['name' => UserRole::OPERATOR->value]);
        $operator->syncPermissions([
            'quotes.view',
            'quotes.create',
            'quotes.edit',
            'quotes.send',
            'quotes.conclude',
            'quotes.reject',
            'customers.view',
            'customers.create',
            'customers.edit',
            'contacts.view',
            'insurers.view',
        ]);

        // Viewer - Solo lectura
        $viewer = Role::firstOrCreate(['name' => UserRole::VIEWER->value]);
        $viewer->syncPermissions([
            'quotes.view',
            'customers.view',
            'contacts.view',
            'insurers.view',
        ]);

        $this->command->info('✅ Roles y permisos creados exitosamente');
    }
}
