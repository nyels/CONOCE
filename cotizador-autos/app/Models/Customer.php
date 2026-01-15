<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Src\Domain\Customer\Enums\CustomerType;

class Customer extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;

    protected $fillable = [
        'uuid',
        'type',
        'name',
        'rfc',
        'curp',
        'email',
        'phone',
        'mobile',
        'street',
        'exterior_number',
        'interior_number',
        'neighborhood',
        'zip_code',
        'city',
        'municipality',
        'state',
        'legal_representative',
        'legal_representative_rfc',
        'source',
        'contact_id',
        'created_by',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => CustomerType::class,
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
            ->logOnly(['name', 'type', 'rfc', 'email', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Cliente {$eventName}");
    }

    // ==========================================
    // Scopes
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePhysical($query)
    {
        return $query->where('type', CustomerType::PHYSICAL);
    }

    public function scopeMoral($query)
    {
        return $query->where('type', CustomerType::MORAL);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('rfc', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    // ==========================================
    // Relaciones
    // ==========================================

    /**
     * Contacto/intermediario que refirió
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Usuario que lo creó
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Cotizaciones del cliente
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    // ==========================================
    // Métodos
    // ==========================================

    /**
     * Verifica si es persona física
     */
    public function isPhysical(): bool
    {
        return $this->type === CustomerType::PHYSICAL;
    }

    /**
     * Verifica si es persona moral
     */
    public function isMoral(): bool
    {
        return $this->type === CustomerType::MORAL;
    }

    /**
     * Obtiene la dirección formateada
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->exterior_number ? "#{$this->exterior_number}" : null,
            $this->interior_number ? "Int. {$this->interior_number}" : null,
        ]);

        $line1 = implode(' ', $parts);
        $line2 = $this->neighborhood;
        $line3 = implode(', ', array_filter([
            $this->city,
            $this->municipality,
            $this->state,
            $this->zip_code ? "C.P. {$this->zip_code}" : null,
        ]));

        return implode("\n", array_filter([$line1, $line2, $line3]));
    }

    /**
     * Obtiene el nombre con RFC para selectores
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->rfc
            ? "{$this->name} ({$this->rfc})"
            : $this->name;
    }

    /**
     * Obtiene las iniciales para avatar
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= mb_strtoupper(mb_substr($word, 0, 1));
        }

        return $initials;
    }

    /**
     * Cuenta las cotizaciones por estado
     */
    public function getQuoteStats(): array
    {
        return $this->quotes()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}
