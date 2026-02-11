<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

/**
 * Historial de contraseñas de usuarios
 * Usado para prevenir reutilización de contraseñas anteriores
 *
 * @property int $id
 * @property int $user_id
 * @property string $password Hash de contraseña anterior
 * @property \Carbon\Carbon $created_at
 */
class PasswordHistory extends Model
{
    const UPDATED_AT = null; // No tiene updated_at

    /**
     * Número máximo de contraseñas a recordar por usuario
     */
    public const MAX_HISTORY = 5;

    protected $fillable = [
        'user_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Usuario al que pertenece este historial
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica si la contraseña ya fue usada por el usuario
     */
    public static function wasUsedRecently(int $userId, string $plainPassword): bool
    {
        $recentPasswords = static::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(self::MAX_HISTORY)
            ->pluck('password');

        foreach ($recentPasswords as $hashedPassword) {
            if (Hash::check($plainPassword, $hashedPassword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Registra una nueva contraseña en el historial
     * y limpia las más antiguas
     */
    public static function recordPassword(int $userId, string $hashedPassword): void
    {
        // Crear nuevo registro
        static::create([
            'user_id' => $userId,
            'password' => $hashedPassword,
        ]);

        // Limpiar registros antiguos (mantener solo MAX_HISTORY)
        $count = static::where('user_id', $userId)->count();

        if ($count > self::MAX_HISTORY) {
            static::where('user_id', $userId)
                ->orderBy('created_at', 'asc')
                ->limit($count - self::MAX_HISTORY)
                ->delete();
        }
    }
}
