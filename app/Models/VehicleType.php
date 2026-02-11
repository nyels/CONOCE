<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Modelo para tipos de vehículo
 * Catálogo administrable por el cliente (AUTO, MOTO, PICK UP, CAMION, etc.)
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property string|null $description
 * @property bool $requires_cargo_description
 * @property float $risk_factor
 * @property string|null $icon
 * @property int $sort_order
 * @property bool $is_active
 */
class VehicleType extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'label',
        'description',
        'requires_cargo_description',
        'risk_factor',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_cargo_description' => 'boolean',
            'risk_factor' => 'decimal:2',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Configuración de Activity Log para trazabilidad
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('vehicle_type')
            ->setDescriptionForEvent(fn(string $eventName) => "Tipo de vehículo '{$this->name}' {$eventName}");
    }

    // ==========================================
    // Scopes
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeRequiresCargo($query)
    {
        return $query->where('requires_cargo_description', true);
    }

    // ==========================================
    // Relaciones
    // ==========================================

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    // ==========================================
    // Métodos
    // ==========================================

    /**
     * Obtiene tipos de vehículo para select
     */
    public static function forSelect(): array
    {
        return static::active()
            ->ordered()
            ->get()
            ->map(fn($type) => [
                'value' => $type->id,
                'label' => $type->label,
                'name' => $type->name,
                'requires_cargo' => $type->requires_cargo_description,
                'risk_factor' => (float) $type->risk_factor,
            ])
            ->toArray();
    }

    /**
     * Verifica si puede ser eliminado (no tiene cotizaciones asociadas)
     */
    public function canBeDeleted(): bool
    {
        return $this->quotes()->count() === 0;
    }
}
