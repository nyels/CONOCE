<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modelo de Personal (Staff)
 *
 * @property int $id
 * @property string $uuid
 * @property string $employee_number
 * @property string $first_name
 * @property string $paternal_surname
 * @property string|null $maternal_surname
 * @property \Carbon\Carbon|null $birth_date
 * @property string|null $curp
 * @property string|null $rfc
 * @property string|null $phone
 * @property string|null $mobile
 * @property int $position_id
 * @property \Carbon\Carbon|null $hire_date
 * @property \Carbon\Carbon|null $termination_date
 * @property bool $is_active
 * @property string|null $notes
 */
class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'uuid',
        'employee_number',
        'first_name',
        'paternal_surname',
        'maternal_surname',
        'birth_date',
        'curp',
        'rfc',
        'phone',
        'mobile',
        'position_id',
        'hire_date',
        'termination_date',
        'is_active',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Boot del modelo
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Staff $staff) {
            if (empty($staff->uuid)) {
                $staff->uuid = (string) Str::uuid();
            }

            // Auto-generar numero de empleado si no existe
            if (empty($staff->employee_number)) {
                $staff->employee_number = static::generateEmployeeNumber();
            }

            // Registrar usuario creador
            if (auth()->check()) {
                $staff->created_by = auth()->id();
            }
        });

        static::updating(function (Staff $staff) {
            if (auth()->check()) {
                $staff->updated_by = auth()->id();
            }
        });
    }

    /**
     * Genera numero de empleado unico
     */
    public static function generateEmployeeNumber(): string
    {
        $year = date('Y');
        $lastNumber = static::withTrashed()
            ->where('employee_number', 'like', "EMP{$year}%")
            ->max('employee_number');

        if ($lastNumber) {
            $sequence = (int) substr($lastNumber, -4) + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('EMP%s%04d', $year, $sequence);
    }

    // ==================== RELACIONES ====================

    /**
     * Puesto del empleado
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Todos los emails del empleado
     */
    public function emails(): HasMany
    {
        return $this->hasMany(StaffEmail::class);
    }

    /**
     * Email principal
     */
    public function primaryEmail(): HasOne
    {
        return $this->hasOne(StaffEmail::class)->where('is_primary', true);
    }

    /**
     * Usuario asociado (si tiene cuenta)
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Usuario que creo el registro
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuario que actualizo el registro
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ==================== SCOPES ====================

    /**
     * Solo personal activo
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filtrar por puesto
     */
    public function scopeByPosition($query, int $positionId)
    {
        return $query->where('position_id', $positionId);
    }

    /**
     * Busqueda por nombre
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
              ->orWhere('paternal_surname', 'like', "%{$term}%")
              ->orWhere('maternal_surname', 'like', "%{$term}%")
              ->orWhere('employee_number', 'like', "%{$term}%")
              ->orWhere('curp', 'like', "%{$term}%")
              ->orWhereHas('emails', fn($e) => $e->where('email', 'like', "%{$term}%"));
        });
    }

    // ==================== ACCESSORS ====================

    /**
     * Nombre completo
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->paternal_surname,
            $this->maternal_surname,
        ]);

        return implode(' ', $parts);
    }

    /**
     * Nombre en formato apellido, nombre
     */
    public function getFormalNameAttribute(): string
    {
        $surnames = array_filter([
            $this->paternal_surname,
            $this->maternal_surname,
        ]);

        return implode(' ', $surnames) . ', ' . $this->first_name;
    }

    /**
     * Email principal (atajo)
     */
    public function getPrimaryEmailAddressAttribute(): ?string
    {
        return $this->primaryEmail?->email;
    }

    /**
     * Edad calculada
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    /**
     * Antiguedad en anios
     */
    public function getSeniorityYearsAttribute(): ?int
    {
        if (!$this->hire_date) {
            return null;
        }

        $endDate = $this->termination_date ?? now();
        return $this->hire_date->diffInYears($endDate);
    }

    // ==================== METODOS ====================

    /**
     * Sincroniza emails del empleado
     *
     * @param array $emails [['email' => '...', 'type' => '...', 'is_primary' => bool], ...]
     */
    public function syncEmails(array $emails): void
    {
        // Eliminar emails existentes
        $this->emails()->delete();

        // Asegurar que solo haya un primario
        $hasPrimary = collect($emails)->contains('is_primary', true);
        $isFirst = true;

        foreach ($emails as $emailData) {
            $isPrimary = $emailData['is_primary'] ?? false;

            // Si no hay primario definido, el primero sera primario
            if (!$hasPrimary && $isFirst) {
                $isPrimary = true;
            }

            $this->emails()->create([
                'email' => strtolower(trim($emailData['email'])),
                'type' => $emailData['type'] ?? 'work',
                'is_primary' => $isPrimary,
            ]);

            $isFirst = false;
        }
    }

    /**
     * Verifica si tiene cuenta de usuario
     */
    public function hasUserAccount(): bool
    {
        return $this->user()->exists();
    }
}
