<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Models\ActivityLog;
use App\Models\User;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // listener lain kalau ada, biarkan
    ];

    public function boot(): void
    {
        parent::boot();

        // LOGIN SUKSES
        Event::listen(Login::class, function (Login $event) {
            // Pastikan tipe-nya App\Models\User
            $user = $event->user instanceof User
                ? $event->user
                : User::find($event->user->getAuthIdentifier());

            if (! $user) {
                return;
            }

            $username = $user->username ?? $user->email ?? ('ID: '.$user->id);

            ActivityLog::record(
                'login',
                "User '{$username}' berhasil login.",
                'info',
                $user
            );
        });

        // LOGOUT
        Event::listen(Logout::class, function (Logout $event) {
            $user = $event->user;

            if (! $user) {
                return;
            }

            $userModel = $user instanceof User
                ? $user
                : User::find($user->getAuthIdentifier());

            if (! $userModel) {
                return;
            }

            $username = $userModel->username ?? $userModel->email ?? ('ID: '.$userModel->id);

            ActivityLog::record(
                'logout',
                "User '{$username}' logout.",
                'info',
                $userModel
            );
        });

        // GAGAL LOGIN
        Event::listen(Failed::class, function (Failed $event) {
            $username = $event->credentials['username']
                ?? $event->credentials['email']
                ?? 'unknown';

            ActivityLog::record(
                'login_failed',
                "Gagal login untuk username '{$username}'.",
                'warning',
                null
            );
        });
    }
}
