<?php

namespace Src\Domain\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domain\Customer\Models\Customer;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'customer_id',
        'agent_id',
        'type',
        'folio',
        'vehicle_data',
        'previous_policy',
        'previous_insurer',
        'status',
        'issued_policy_number',
        'validity_start',
        'validity_end'
    ];

    protected $casts = [
        'vehicle_data' => 'array', // PostgreSQL convierte el JSON a Array automáticamente aquí
        'validity_start' => 'date',
        'validity_end' => 'date',
    ];

    // Relación con las opciones (las N aseguradoras cotizadas)
    public function options(): HasMany
    {
        return $this->hasMany(QuoteOption::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
