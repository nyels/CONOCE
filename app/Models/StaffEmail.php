<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para emails de personal
 *
 * @property int $id
 * @property int $staff_id
 * @property string $email
 * @property bool $is_primary
 * @property string $type
 */
class StaffEmail extends Model
{
    protected $fillable = [
        'staff_id',
        'email',
        'is_primary',
        'type',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Empleado al que pertenece
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Boot: normalizar email antes de guardar
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (StaffEmail $email) {
            $email->email = strtolower(trim($email->email));
        });
    }
}
