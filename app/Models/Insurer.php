<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Insurer extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;

    protected $fillable = [
        'uuid',
        'name',
        'short_name',
        'code',
        'email',
        'phone',
        'website',
        'logo_path',
        'primary_color',
        'notes',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
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
            ->logOnly(['name', 'code', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Aseguradora {$eventName}");
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

    // ==========================================
    // Relaciones
    // ==========================================

    /**
     * Configuraciones financieras (historial)
     */
    public function financialSettings()
    {
        return $this->hasMany(InsurerFinancialSetting::class);
    }

    /**
     * Configuración financiera vigente
     */
    public function currentFinancialSetting()
    {
        return $this->hasOne(InsurerFinancialSetting::class)
            ->whereNull('valid_until')
            ->latest('valid_from');
    }

    /**
     * Opciones de cotización
     */
    public function quoteOptions()
    {
        return $this->hasMany(QuoteOption::class);
    }

    // ==========================================
    // Métodos
    // ==========================================

    /**
     * Obtiene la configuración financiera para una fecha específica
     */
    public function getFinancialSettingForDate(\DateTimeInterface $date = null)
    {
        $date = $date ?? now();

        return $this->financialSettings()
            ->where('valid_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', $date);
            })
            ->orderBy('valid_from', 'desc')
            ->first();
    }

    /**
     * Obtiene el derecho de póliza actual en centavos
     */
    public function getCurrentPolicyFeeCents(): int
    {
        return $this->currentFinancialSetting?->policy_fee_cents ?? 0;
    }

    /**
     * Obtiene los recargos actuales
     */
    public function getCurrentSurcharges(): array
    {
        $setting = $this->currentFinancialSetting;

        return [
            'semiannual' => $setting?->surcharge_semiannual ?? 0,
            'quarterly' => $setting?->surcharge_quarterly ?? 0,
            'monthly' => $setting?->surcharge_monthly ?? 0,
        ];
    }

    /**
     * Obtiene el logo URL o un placeholder
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path) {
            // Las imágenes están en public/images
            return asset($this->logo_path);
        }

        // Placeholder con las iniciales
        $initials = mb_strtoupper(mb_substr($this->name, 0, 2));
        return "https://ui-avatars.com/api/?name={$initials}&size=128&background={$this->primary_color}&color=fff";
    }

    /**
     * Nombre para mostrar
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->short_name ?? $this->name;
    }
}
