<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Src\Domain\Quote\Enums\PaymentFrequency;
use Src\Domain\Quote\Enums\CoveragePackage;
use Src\Domain\Shared\ValueObjects\Money;

class QuoteOption extends Model
{
    use HasFactory,
        LogsActivity;

    protected $fillable = [
        'quote_id',
        'insurer_id',
        'option_number',
        'coverage_package',
        'coverages',
        'payment_frequency',
        'net_premium_cents',
        'policy_fee_cents',
        'surcharge_cents',
        'iva_cents',
        'total_premium_cents',
        'first_payment_cents',
        'subsequent_payment_cents',
        'is_selected',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'coverage_package' => CoveragePackage::class,
            'payment_frequency' => PaymentFrequency::class,
            'coverages' => 'array',
            'net_premium_cents' => 'integer',
            'policy_fee_cents' => 'integer',
            'surcharge_cents' => 'integer',
            'iva_cents' => 'integer',
            'total_premium_cents' => 'integer',
            'first_payment_cents' => 'integer',
            'subsequent_payment_cents' => 'integer',
            'is_selected' => 'boolean',
            'option_number' => 'integer',
        ];
    }

    /**
     * Configuración de Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_selected', 'payment_frequency', 'total_premium_cents'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ==========================================
    // Relaciones
    // ==========================================

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function insurer()
    {
        return $this->belongsTo(Insurer::class);
    }

    // ==========================================
    // Accessors - Valores Monetarios
    // ==========================================

    /**
     * Prima neta como Money object
     */
    public function getNetPremiumMoneyAttribute(): Money
    {
        return Money::fromCents($this->net_premium_cents);
    }

    /**
     * Prima neta como decimal
     */
    public function getNetPremiumAttribute(): float
    {
        return $this->net_premium_cents / 100;
    }

    /**
     * Derecho de póliza como Money object
     */
    public function getPolicyFeeMoneyAttribute(): Money
    {
        return Money::fromCents($this->policy_fee_cents);
    }

    /**
     * Derecho de póliza como decimal
     */
    public function getPolicyFeeAttribute(): float
    {
        return $this->policy_fee_cents / 100;
    }

    /**
     * Recargo como Money object
     */
    public function getSurchargeMoneyAttribute(): Money
    {
        return Money::fromCents($this->surcharge_cents);
    }

    /**
     * Recargo como decimal
     */
    public function getSurchargeAttribute(): float
    {
        return $this->surcharge_cents / 100;
    }

    /**
     * IVA como Money object
     */
    public function getIvaMoneyAttribute(): Money
    {
        return Money::fromCents($this->iva_cents);
    }

    /**
     * IVA como decimal
     */
    public function getIvaAttribute(): float
    {
        return $this->iva_cents / 100;
    }

    /**
     * Prima total como Money object
     */
    public function getTotalPremiumMoneyAttribute(): Money
    {
        return Money::fromCents($this->total_premium_cents);
    }

    /**
     * Prima total como decimal
     */
    public function getTotalPremiumAttribute(): float
    {
        return $this->total_premium_cents / 100;
    }

    /**
     * Primer pago como Money object
     */
    public function getFirstPaymentMoneyAttribute(): Money
    {
        return Money::fromCents($this->first_payment_cents);
    }

    /**
     * Primer pago como decimal
     */
    public function getFirstPaymentAttribute(): float
    {
        return $this->first_payment_cents / 100;
    }

    /**
     * Pagos subsecuentes como Money object
     */
    public function getSubsequentPaymentMoneyAttribute(): Money
    {
        return Money::fromCents($this->subsequent_payment_cents);
    }

    /**
     * Pagos subsecuentes como decimal
     */
    public function getSubsequentPaymentAttribute(): float
    {
        return $this->subsequent_payment_cents / 100;
    }

    // ==========================================
    // Métodos de Coberturas
    // ==========================================

    /**
     * Obtiene una cobertura específica
     */
    public function getCoverage(string $key)
    {
        return data_get($this->coverages, $key);
    }

    /**
     * Verifica si tiene una cobertura específica
     */
    public function hasCoverage(string $key): bool
    {
        $coverage = $this->getCoverage($key);
        return $coverage !== null && ($coverage['included'] ?? false);
    }

    /**
     * Obtiene el monto de una cobertura
     */
    public function getCoverageAmount(string $key): ?float
    {
        $coverage = $this->getCoverage($key);
        return $coverage['amount'] ?? null;
    }

    /**
     * Obtiene el deducible de una cobertura
     */
    public function getCoverageDeductible(string $key): ?float
    {
        $coverage = $this->getCoverage($key);
        return $coverage['deductible'] ?? null;
    }

    /**
     * Obtiene todas las coberturas como array estructurado para UI
     */
    public function getCoveragesForDisplay(): array
    {
        $coverages = $this->coverages ?? [];
        $result = [];

        $coverageLabels = [
            'material_damage' => 'Daños Materiales',
            'total_theft' => 'Robo Total',
            'liability' => 'Responsabilidad Civil',
            'medical_expenses' => 'Gastos Médicos',
            'driver_accident' => 'Accidentes del Conductor',
            'legal_protection' => 'Protección Legal',
            'roadside_assistance' => 'Asistencia Vial',
            'extended_coverage' => 'Extensión de Cobertura',
            'cargo_damage' => 'Daños por Carga',
        ];

        foreach ($coverages as $key => $coverage) {
            $result[] = [
                'key' => $key,
                'label' => $coverageLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)),
                'included' => $coverage['included'] ?? false,
                'amount' => isset($coverage['amount'])
                    ? '$' . number_format($coverage['amount'], 2)
                    : null,
                'deductible' => isset($coverage['deductible'])
                    ? number_format($coverage['deductible'], 0) . '%'
                    : null,
                'notes' => $coverage['notes'] ?? null,
            ];
        }

        return $result;
    }

    // ==========================================
    // Métodos de Pagos
    // ==========================================

    /**
     * Obtiene el número de pagos según la frecuencia
     */
    public function getPaymentsCountAttribute(): int
    {
        return $this->payment_frequency->numberOfPayments();
    }

    /**
     * Obtiene el desglose de pagos
     */
    public function getPaymentBreakdown(): array
    {
        $payments = [];
        $count = $this->payments_count;

        for ($i = 1; $i <= $count; $i++) {
            $payments[] = [
                'number' => $i,
                'label' => $i === 1 ? 'Primer pago' : "Pago {$i}",
                'amount_cents' => $i === 1 ? $this->first_payment_cents : $this->subsequent_payment_cents,
                'amount' => $i === 1 ? $this->first_payment : $this->subsequent_payment,
                'formatted' => '$' . number_format(
                    $i === 1 ? $this->first_payment : $this->subsequent_payment,
                    2
                ),
            ];
        }

        return $payments;
    }

    // ==========================================
    // Métodos de Cálculo
    // ==========================================

    /**
     * Recalcula totales basándose en los valores individuales
     */
    public function recalculateTotals(): void
    {
        $total = $this->net_premium_cents
            + $this->policy_fee_cents
            + $this->surcharge_cents
            + $this->iva_cents;

        $this->total_premium_cents = $total;

        // Calcular pagos según frecuencia
        $paymentsCount = $this->payment_frequency->numberOfPayments();

        if ($paymentsCount === 1) {
            $this->first_payment_cents = $total;
            $this->subsequent_payment_cents = 0;
        } else {
            // Primer pago generalmente es mayor (incluye derecho de póliza completo)
            $basePayment = (int) floor($total / $paymentsCount);
            $remainder = $total - ($basePayment * $paymentsCount);

            $this->first_payment_cents = $basePayment + $remainder;
            $this->subsequent_payment_cents = $basePayment;
        }
    }

    // ==========================================
    // Métodos Auxiliares
    // ==========================================

    /**
     * Obtiene el nombre de la aseguradora
     */
    public function getInsurerNameAttribute(): string
    {
        return $this->insurer?->name ?? 'Sin aseguradora';
    }

    /**
     * Obtiene el resumen para listados
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'option_number' => $this->option_number,
            'insurer' => [
                'id' => $this->insurer_id,
                'name' => $this->insurer_name,
                'logo' => $this->insurer?->logo_url,
            ],
            'package' => $this->coverage_package->label(),
            'payment_frequency' => $this->payment_frequency->label(),
            'total_premium' => $this->total_premium,
            'total_premium_formatted' => '$' . number_format($this->total_premium, 2),
            'first_payment' => $this->first_payment,
            'first_payment_formatted' => '$' . number_format($this->first_payment, 2),
            'is_selected' => $this->is_selected,
        ];
    }

    /**
     * Compara con otra opción y retorna la diferencia
     */
    public function compareTo(QuoteOption $other): array
    {
        return [
            'net_premium_diff' => $this->net_premium_cents - $other->net_premium_cents,
            'total_diff' => $this->total_premium_cents - $other->total_premium_cents,
            'is_cheaper' => $this->total_premium_cents < $other->total_premium_cents,
            'percentage_diff' => $other->total_premium_cents > 0
                ? (($this->total_premium_cents - $other->total_premium_cents) / $other->total_premium_cents) * 100
                : 0,
        ];
    }
}
