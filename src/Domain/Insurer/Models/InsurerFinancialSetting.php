<?php

namespace Src\Domain\Insurer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsurerFinancialSetting extends Model
{
    protected $fillable = [
        'insurer_id',
        'policy_fee',
        'surcharge_semiannual',
        'surcharge_quarterly',
        'surcharge_monthly',
        'valid_from',
        'valid_until'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'policy_fee' => 'decimal:2',
    ];

    public function insurer(): BelongsTo
    {
        return $this->belongsTo(Insurer::class);
    }
}
