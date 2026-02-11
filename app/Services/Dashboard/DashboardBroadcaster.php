<?php

namespace App\Services\Dashboard;

use App\Events\DashboardDataChanged;
use Illuminate\Support\Facades\Cache;

class DashboardBroadcaster
{
    private const COOLDOWN_SECONDS = 3;
    private const CACHE_KEY = 'dashboard:broadcast_cooldown';

    public static function notify(string $reason = 'quote_updated'): void
    {
        if (Cache::has(self::CACHE_KEY)) {
            return;
        }

        Cache::put(self::CACHE_KEY, true, self::COOLDOWN_SECONDS);

        DashboardDataChanged::dispatch($reason);
    }
}
