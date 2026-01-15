<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InsurerFinancialSetting extends Model
{
    use HasFactory,
        LogsActivity;

    protected $fillable = [
        'insurer_id',
        'policy_fee_cents',
        'surcharge_semiannual',
        'surcharge_quarterly',
        'surcharge_monthly',
        'valid_from',
        'valid_until',
        'created_by',
        'change_reason',
    ];

    protected function casts(): array
    {
        return [
            'policy_fee_cents' => 'integer',
            'surcharge_semiannual' => 'decimal:4',
            'surcharge_quarterly' => 'decimal:4',
            'surcharge_monthly' => 'decimal:4',
            'valid_from' => 'date',
            'valid_until' => 'date',
        ];
    }

    /**
     * Configuración de Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Configuración financiera {$eventName}");
    }

    // ==========================================
    // Relaciones
    // ==========================================

    public function insurer()
    {
        return $this->belongsTo(Insurer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ==========================================
    // Métodos
    // ==========================================

    /**
     * Verifica si esta configuración está vigente
     */
    public function isActive(): bool
    {
        $now = now()->toDateString();

        return $this->valid_from <= $now
            && ($this->valid_until === null || $this->valid_until >= $now);
    }

    /**
     * Obtiene el derecho de póliza en formato monetario (decimal)
     */
    public function getPolicyFeeAttribute(): float
    {
        return $this->policy_fee_cents / 100;
    }

    /**
     * Establece el derecho de póliza desde formato decimal
     */
    public function setPolicyFeeAttribute(float $value): void
    {
        $this->attributes['policy_fee_cents'] = (int) round($value * 100);
    }

    /**
     * Obtiene el recargo por frecuencia de pago
     */
    public function getSurchargeForFrequency(string $frequency): float
    {
        return match ($frequency) {
            'SEMIANNUAL' => $this->surcharge_semiannual,
            'QUARTERLY' => $this->surcharge_quarterly,
            'MONTHLY' => $this->surcharge_monthly,
            default => 0.0,
        };
    }

    /**
     * Calcula el recargo para una prima neta
     */
    public function calculateSurcharge(int $netPremiumCents, string $frequency): int
    {
        $rate = $this->getSurchargeForFrequency($frequency);
        return (int) round($netPremiumCents * $rate);
    }

    /**
     * Obtiene un resumen de los recargos para UI
     */
    public function getSurchargesSummary(): array
    {
        return [
            [
                'frequency' => 'Semestral',
                'rate' => $this->surcharge_semiannual,
                'percentage' => $this->surcharge_semiannual * 100 . '%',
            ],
            [
                'frequency' => 'Trimestral',
                'rate' => $this->surcharge_quarterly,
                'percentage' => $this->surcharge_quarterly * 100 . '%',
            ],
            [
                'frequency' => 'Mensual',
                'rate' => $this->surcharge_monthly,
                'percentage' => $this->surcharge_monthly * 100 . '%',
            ],
        ];
    }
}
