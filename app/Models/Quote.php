<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Src\Domain\Quote\Enums\QuoteStatus;
use Src\Domain\Quote\Enums\QuoteType;
use Src\Domain\Quote\Enums\CoveragePackage;
use App\Models\Traits\HasFolio;

class Quote extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity,
        HasFolio;

    protected $fillable = [
        'uuid',
        'folio',
        'customer_id',
        'contact_id',
        'agent_id',
        'type',
        'status',
        'vehicle_data',
        'previous_policy_number',
        'previous_insurer',
        'previous_premium_cents',
        'previous_expiry_date',
        'package_type',
        'options_count',
        'quote_valid_until',
        'sent_at',
        'first_reminder_at',
        'second_reminder_at',
        'concluded_option_id',
        'issued_policy_number',
        'policy_start_date',
        'policy_end_date',
        'concluded_at',
        'rejection_reason',
        'rejected_at',
        'internal_notes',
        'customer_notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => QuoteType::class,
            'status' => QuoteStatus::class,
            'package_type' => CoveragePackage::class,
            'vehicle_data' => 'array',
            'previous_expiry_date' => 'date',
            'quote_valid_until' => 'date',
            'sent_at' => 'datetime',
            'first_reminder_at' => 'datetime',
            'second_reminder_at' => 'datetime',
            'concluded_at' => 'datetime',
            'rejected_at' => 'datetime',
            'policy_start_date' => 'date',
            'policy_end_date' => 'date',
            'previous_premium_cents' => 'integer',
            'options_count' => 'integer',
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
            if (empty($model->folio)) {
                $model->folio = $model->generateFolio('COT');
            }
            if (empty($model->status)) {
                $model->status = QuoteStatus::DRAFT;
            }
        });

        // Actualizar contador de opciones
        static::saved(function ($model) {
            $model->updateOptionsCount();
        });
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
            ->useLogName('quote')
            ->setDescriptionForEvent(fn(string $eventName) => "Cotización {$this->folio} {$eventName}");
    }

    // ==========================================
    // Scopes
    // ==========================================

    public function scopeActive($query)
    {
        return $query->whereIn('status', QuoteStatus::getActiveStatuses());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', QuoteStatus::DRAFT);
    }

    public function scopeSent($query)
    {
        return $query->where('status', QuoteStatus::SENT);
    }

    public function scopeConcreted($query)
    {
        return $query->where('status', QuoteStatus::CONCRETED);
    }

    public function scopeIssued($query)
    {
        return $query->where('status', QuoteStatus::ISSUED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', QuoteStatus::REJECTED);
    }

    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('folio', 'like', "%{$term}%")
                ->orWhere('issued_policy_number', 'like', "%{$term}%")
                ->orWhereHas('customer', function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('rfc', 'like', "%{$term}%");
                });
        });
    }

    // ==========================================
    // Relaciones
    // ==========================================

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function options()
    {
        return $this->hasMany(QuoteOption::class)->orderBy('option_number');
    }

    public function concludedOption()
    {
        return $this->belongsTo(QuoteOption::class, 'concluded_option_id');
    }

    public function selectedOption()
    {
        return $this->hasOne(QuoteOption::class)->where('is_selected', true);
    }

    // ==========================================
    // Métodos de Estado
    // ==========================================

    /**
     * Verifica si la cotización puede ser editada
     */
    public function isEditable(): bool
    {
        return $this->status->isEditable();
    }

    /**
     * Verifica si la cotización está en un estado activo
     */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * Verifica si la cotización está en un estado final
     */
    public function isFinal(): bool
    {
        return $this->status->isFinal();
    }

    /**
     * Verifica si puede transicionar a un nuevo estado
     */
    public function canTransitionTo(QuoteStatus $newStatus): bool
    {
        return $this->status->canTransitionTo($newStatus);
    }

    /**
     * Transiciona a un nuevo estado con validación
     */
    public function transitionTo(QuoteStatus $newStatus): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        $this->status = $newStatus;

        // Establecer timestamps según el estado
        match ($newStatus) {
            QuoteStatus::SENT => $this->sent_at = now(),
            QuoteStatus::CONCRETED => $this->concluded_at = now(),
            QuoteStatus::REJECTED => $this->rejected_at = now(),
            default => null,
        };

        return $this->save();
    }

    // ==========================================
    // Métodos de Vehículo
    // ==========================================

    /**
     * Obtiene un dato específico del vehículo
     */
    public function getVehicleAttribute(string $key, $default = null)
    {
        return data_get($this->vehicle_data, $key, $default);
    }

    /**
     * Obtiene la descripción completa del vehículo
     */
    public function getVehicleDescriptionAttribute(): string
    {
        $data = $this->vehicle_data ?? [];

        return implode(' ', array_filter([
            $data['brand'] ?? '',
            $data['model'] ?? '',
            $data['year'] ?? '',
            $data['version'] ?? '',
        ]));
    }

    /**
     * Obtiene el tipo de vehículo
     */
    public function getVehicleTypeAttribute(): ?string
    {
        return $this->vehicle_data['type'] ?? null;
    }

    // ==========================================
    // Métodos Financieros
    // ==========================================

    /**
     * Obtiene la prima anterior en formato decimal
     */
    public function getPreviousPremiumAttribute(): ?float
    {
        return $this->previous_premium_cents
            ? $this->previous_premium_cents / 100
            : null;
    }

    /**
     * Obtiene la opción más económica
     */
    public function getCheapestOption(): ?QuoteOption
    {
        return $this->options()->orderBy('total_premium_cents')->first();
    }

    /**
     * Obtiene la opción más cara
     */
    public function getMostExpensiveOption(): ?QuoteOption
    {
        return $this->options()->orderByDesc('total_premium_cents')->first();
    }

    // ==========================================
    // Métodos Auxiliares
    // ==========================================

    /**
     * Actualiza el contador de opciones
     */
    public function updateOptionsCount(): void
    {
        $count = $this->options()->count();
        if ($this->options_count !== $count) {
            $this->updateQuietly(['options_count' => $count]);
        }
    }

    /**
     * Marca una opción como seleccionada
     */
    public function selectOption(QuoteOption $option): bool
    {
        if ($option->quote_id !== $this->id) {
            return false;
        }

        // Deseleccionar otras
        $this->options()->update(['is_selected' => false]);

        // Seleccionar esta
        $option->update(['is_selected' => true]);

        return true;
    }

    /**
     * Verifica si está expirada (pasó la fecha de vigencia)
     */
    public function isExpired(): bool
    {
        if (!$this->quote_valid_until) {
            return false;
        }

        return $this->quote_valid_until->isPast() && $this->status === QuoteStatus::SENT;
    }

    /**
     * Marca como expirada si corresponde
     */
    public function checkAndMarkExpired(): bool
    {
        if ($this->isExpired()) {
            return $this->transitionTo(QuoteStatus::EXPIRED);
        }

        return false;
    }

    /**
     * Genera una URL pública para compartir
     */
    public function getPublicUrl(): string
    {
        return route('quotes.public', $this->uuid);
    }

    /**
     * Obtiene el resumen para listados
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'folio' => $this->folio,
            'type' => $this->type->label(),
            'status' => $this->status->label(),
            'status_color' => $this->status->color(),
            'customer' => $this->customer->name,
            'vehicle' => $this->vehicle_description,
            'options_count' => $this->options_count,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
