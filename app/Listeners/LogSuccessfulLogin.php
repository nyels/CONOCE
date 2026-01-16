<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\Activitylog\Models\Activity;

use App\Models\User;

class LogSuccessfulLogin
{
    // ...

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        /** @var User $user */
        $user = $event->user;

        if ($user) {
            activity('auth')
                ->performedOn($user)
                ->causedBy($user)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Inicio de sesión exitoso');
        }
    }
}
