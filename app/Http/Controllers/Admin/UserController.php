<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Src\Domain\Shared\Enums\UserRole;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('name')
            ->get()
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role?->value,
                'role_label' => $user->role_label,
                'role_color' => $user->role_color,
                'phone' => $user->phone,
                'is_active' => $user->is_active,
                'last_login_at' => $user->last_login_at?->format('d/m/Y H:i'),
            ]);

        // Get roles for dropdown (exclude super_admin unless current user is super_admin)
        $roles = collect(UserRole::toSelectArray())
            ->when(!auth()->user()->isSuperAdmin(), function ($collection) {
                return $collection->filter(fn($role) => $role['value'] !== UserRole::SUPER_ADMIN->value);
            })
            ->values()
            ->all();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::defaults()],
            'role' => 'required|string|in:' . implode(',', array_column(UserRole::cases(), 'value')),
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        // Prevent creating super_admin unless current user is super_admin
        if ($validated['role'] === UserRole::SUPER_ADMIN->value && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'No tienes permiso para crear super administradores');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing super_admin unless current user is super_admin
        if ($user->role === UserRole::SUPER_ADMIN && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'No tienes permiso para editar super administradores');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::defaults()],
            'role' => 'required|string|in:' . implode(',', array_column(UserRole::cases(), 'value')),
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        // Prevent assigning super_admin role unless current user is super_admin
        if ($validated['role'] === UserRole::SUPER_ADMIN->value && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'No tienes permiso para asignar el rol de super administrador');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->phone = $validated['phone'] ?? null;
        $user->is_active = $validated['is_active'] ?? true;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta');
        }

        // Prevent deleting super_admin unless current user is super_admin
        if ($user->role === UserRole::SUPER_ADMIN && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'No tienes permiso para eliminar super administradores');
        }

        // Check for related quotes
        if ($user->quotes()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene cotizaciones asociadas');
        }

        $user->delete();

        return back()->with('success', 'Usuario eliminado exitosamente');
    }
}
