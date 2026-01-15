<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Src\Domain\Contact\Enums\ContactType;

class Contact extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;

    protected $fillable = [
        'uuid',
        'type',
        'name',
        'email',
        'phone',
        'mobile',
        'cnsf_license',
        'license_expiry',
        'commission_rate',
        'parent_agent_id',
        'created_by',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => ContactType::class,
            'license_expiry' => 'date',
            'commission_rate' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Configuración de Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'type', 'email', 'commission_rate', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Contacto {$eventName}");
    }

    // ==========================================
    // Scopes
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAgents($query)
    {
        return $query->where('type', ContactType::AGENT);
    }

    public function scopeSubAgents($query)
    {
        return $query->where('type', ContactType::SUB_AGENT);
    }

    public function scopeEmployees($query)
    {
        return $query->where('type', ContactType::EMPLOYEE);
    }

    public function scopeWithActiveAgents($query)
    {
        return $query->active()->whereIn('type', [
            ContactType::AGENT->value,
            ContactType::SUB_AGENT->value,
        ]);
    }

    // ==========================================
    // Relaciones
    // ==========================================

    /**
     * Agente padre (para subagentes)
     */
    public function parentAgent()
    {
        return $this->belongsTo(Contact::class, 'parent_agent_id');
    }

    /**
     * Subagentes de este agente
     */
    public function subAgents()
    {
        return $this->hasMany(Contact::class, 'parent_agent_id');
    }

    /**
     * Usuario que lo creó
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Clientes referidos por este contacto
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Cotizaciones asociadas
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    // ==========================================
    // Métodos
    // ==========================================

    /**
     * Verifica si es un agente
     */
    public function isAgent(): bool
    {
        return $this->type === ContactType::AGENT;
    }

    /**
     * Verifica si es un subagente
     */
    public function isSubAgent(): bool
    {
        return $this->type === ContactType::SUB_AGENT;
    }

    /**
     * Verifica si puede recibir comisión
     */
    public function hasCommission(): bool
    {
        return $this->type->hasCommission() && $this->commission_rate > 0;
    }

    /**
     * Verifica si la cédula CNSF está vigente
     */
    public function hasValidLicense(): bool
    {
        if (!$this->cnsf_license || !$this->license_expiry) {
            return false;
        }

        return $this->license_expiry->isFuture();
    }

    /**
     * Calcula la comisión para un monto dado
     */
    public function calculateCommission(int $amountCents): int
    {
        return (int) round($amountCents * $this->commission_rate);
    }

    /**
     * Obtiene el nombre con tipo para selectores
     */
    public function getFullDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->type->label()})";
    }
}
