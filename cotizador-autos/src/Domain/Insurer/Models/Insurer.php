<?php

namespace Src\Domain\Insurer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Insurer extends Model
{
    // Protegemos la asignación masiva
    protected $fillable = ['name', 'logo_path', 'is_active'];

    // Relación: Una aseguradora tiene una configuración financiera vigente
    public function currentSettings(): HasOne
    {
        return $this->hasOne(InsurerFinancialSetting::class)
            ->latestOfMany(); // Toma la más reciente
    }
}
