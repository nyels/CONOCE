<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\PasswordHistory;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Src\Domain\Shared\Enums\UserRole;

/**
 * Controlador de Usuarios con seguridad bancaria
 * - Login por username
 * - Contraseñas fuertes con expiración cada 90 días
 * - Historial de últimas 5 contraseñas
 * - Vinculación con personal
 */
class UserController extends Controller
{
    /**
     * Lista de usuarios con información de seguridad
     */
    public function index(Request $request)
    {
        $query = User::with('staff:id,first_name,paternal_surname,maternal_surname')
            ->select([
                'id',
                'name',
                'username',
                'email',
                'role',
                'phone',
                'staff_id',
                'is_active',
                'password_expires_at',
                'last_login_at',
                'failed_login_attempts',
                'created_at',
            ])
            ->orderBy('name');

        // Filtro por rol
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Filtro por estado
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Búsqueda
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20)->through(fn(User $user) => [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role?->value,
            'role_label' => $user->role_label,
            'role_color' => $user->role_color,
            'phone' => $user->phone,
            'staff' => $user->staff ? $user->staff->full_name : null,
            'staff_id' => $user->staff_id,
            'is_active' => $user->is_active,
            'password_expires_at' => $user->password_expires_at?->format('d/m/Y'),
            'password_days_remaining' => $user->password_days_remaining,
            'password_expired' => $user->isPasswordExpired(),
            'last_login_at' => $user->last_login_at?->format('d/m/Y H:i'),
            'failed_login_attempts' => $user->failed_login_attempts,
        ]);

        // Roles para dropdown (excluir super_admin si no es super_admin)
        $roles = collect(UserRole::toSelectArray())
            ->when(!auth()->user()->isSuperAdmin(), function ($collection) {
                return $collection->filter(fn($role) => $role['value'] !== UserRole::SUPER_ADMIN->value);
            })
            ->values()
            ->all();

        // Personal sin usuario asignado para selector
        $availableStaff = Staff::active()
            ->whereDoesntHave('user')
            ->orderBy('paternal_surname')
            ->get()
            ->map(fn(Staff $s) => [
                'id' => $s->id,
                'name' => $s->full_name,
                'employee_number' => $s->employee_number,
            ]);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'availableStaff' => $availableStaff,
            'filters' => $request->only(['search', 'role', 'active']),
        ]);
    }

    /**
     * Crear nuevo usuario con seguridad completa
     *
     * El nombre del usuario se obtiene del personal asociado.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Verificar permisos para crear super_admin
            if ($validated['role'] === UserRole::SUPER_ADMIN->value && !auth()->user()->isSuperAdmin()) {
                return back()->withErrors(['error' => 'No tienes permiso para crear super administradores']);
            }

            // Obtener el personal para derivar el nombre
            $staff = Staff::findOrFail($validated['staff_id']);

            $user = User::create([
                'name' => $staff->full_name, // Nombre derivado del personal
                'username' => $validated['username'],
                'email' => $staff->primaryEmail?->email, // Email del personal si existe
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'phone' => $staff->mobile, // Teléfono del personal
                'staff_id' => $validated['staff_id'],
                'is_active' => $validated['is_active'] ?? true,
                'password_changed_at' => now(),
                'password_expires_at' => now()->addDays(User::PASSWORD_EXPIRY_DAYS),
            ]);

            // Registrar contraseña inicial en historial
            PasswordHistory::recordPassword($user->id, $user->password);

            DB::commit();

            Log::info('Usuario creado', [
                'user_id' => $user->id,
                'username' => $user->username,
                'staff_id' => $staff->id,
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', "Usuario {$user->username} creado exitosamente");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear usuario', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->withErrors(['server' => 'Error al crear el usuario. Por favor intente nuevamente.']);
        }
    }

    /**
     * Actualizar usuario
     *
     * El nombre del usuario se actualiza desde el personal asociado.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Verificar permisos para editar super_admin
        if ($user->role === UserRole::SUPER_ADMIN && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'No tienes permiso para editar super administradores']);
        }

        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Verificar permisos para asignar super_admin
            if ($validated['role'] === UserRole::SUPER_ADMIN->value && !auth()->user()->isSuperAdmin()) {
                return back()->withErrors(['error' => 'No tienes permiso para asignar el rol de super administrador']);
            }

            // Obtener el personal para actualizar datos derivados
            $staff = Staff::findOrFail($validated['staff_id']);

            $user->name = $staff->full_name; // Nombre derivado del personal
            $user->username = $validated['username'];
            $user->email = $staff->primaryEmail?->email; // Email del personal
            $user->role = $validated['role'];
            $user->phone = $staff->mobile; // Teléfono del personal
            $user->staff_id = $validated['staff_id'];
            $user->is_active = $validated['is_active'] ?? true;

            // Si se proporciona nueva contraseña, usar método seguro
            if (!empty($validated['password'])) {
                $user->updatePassword($validated['password']);
            }

            $user->save();

            DB::commit();

            Log::info('Usuario actualizado', [
                'user_id' => $user->id,
                'staff_id' => $staff->id,
                'updated_by' => auth()->id(),
            ]);

            return back()->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al actualizar usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['server' => 'Error al actualizar el usuario.']);
        }
    }

    /**
     * Eliminar usuario (soft delete)
     */
    public function destroy(User $user)
    {
        // No permitir eliminar su propia cuenta
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta']);
        }

        // Verificar permisos para eliminar super_admin
        if ($user->role === UserRole::SUPER_ADMIN && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'No tienes permiso para eliminar super administradores']);
        }

        // Verificar dependencias
        if ($user->quotes()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar: tiene cotizaciones asociadas. Considere desactivar el usuario.']);
        }

        try {
            $userName = $user->name;
            $user->delete();

            Log::info('Usuario eliminado', [
                'user_id' => $user->id,
                'deleted_by' => auth()->id(),
            ]);

            return back()->with('success', "Usuario {$userName} eliminado exitosamente");
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['server' => 'Error al eliminar el usuario.']);
        }
    }

    /**
     * Toggle estado activo/inactivo
     */
    public function toggleActive(User $user)
    {
        // No permitir desactivar su propia cuenta
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes desactivar tu propia cuenta']);
        }

        // Verificar permisos para super_admin
        if ($user->role === UserRole::SUPER_ADMIN && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'No tienes permiso para modificar super administradores']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activado' : 'desactivado';

        Log::info('Estado de usuario cambiado', [
            'user_id' => $user->id,
            'new_status' => $user->is_active,
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', "Usuario {$status} exitosamente");
    }

    /**
     * Forzar cambio de contraseña (para admin)
     */
    public function forcePasswordChange(User $user)
    {
        // Solo super_admin o admin pueden forzar cambio
        if (!auth()->user()->isAdmin()) {
            return back()->withErrors(['error' => 'No tienes permiso para esta acción']);
        }

        $user->update([
            'password_expires_at' => now()->subDay(), // Expirar inmediatamente
        ]);

        Log::info('Cambio de contraseña forzado', [
            'user_id' => $user->id,
            'forced_by' => auth()->id(),
        ]);

        return back()->with('success', "Se ha forzado el cambio de contraseña para {$user->name}");
    }

    /**
     * Resetear intentos de login fallidos
     */
    public function resetFailedLogins(User $user)
    {
        $user->resetFailedLogins();

        Log::info('Intentos fallidos reseteados', [
            'user_id' => $user->id,
            'reset_by' => auth()->id(),
        ]);

        return back()->with('success', 'Intentos de login fallidos reseteados');
    }
}
