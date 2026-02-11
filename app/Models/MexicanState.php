<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MexicanState extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('mexican_state')
            ->setDescriptionForEvent(fn(string $eventName) => "Estado '{$this->name}' {$eventName}");
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Métodos

    public static function forSelect(): array
    {
        return static::active()
            ->ordered()
            ->get()
            ->map(fn($state) => [
                'value' => $state->name,
                'label' => $state->name,
            ])
            ->toArray();
    }
}
