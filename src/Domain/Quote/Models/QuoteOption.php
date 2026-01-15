<?php

namespace Src\Domain\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domain\Insurer\Models\Insurer;

class QuoteOption extends Model
{
    protected $fillable = [
        'quote_id',
        'insurer_id',
        'coverage_package',
        'coverages',
        'payment_frequency',
        'net_premium',
        'policy_fee',
        'surcharge',
        'tax',
        'total_premium',
        'first_payment',
        'subsequent_payments'
    ];

    protected $casts = [
        'coverages' => 'array', // JSON a Array
        'net_premium' => 'decimal:2',
        'total_premium' => 'decimal:2',
    ];

    public function insurer(): BelongsTo
    {
        return $this->belongsTo(Insurer::class);
    }
}
