<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(
            \App\Repository\User\UserRepositoryInterface::class,
            \App\Repository\User\UserRepository::class
        );

        $this->app->bind(
            \App\Repository\PasswordReset\PasswordResetRepositoryInterface::class,
            \App\Repository\PasswordReset\PasswordResetRepository::class
        );

        $this->app->bind(
            \App\Repository\Call\CallRepositoryInterface::class,
            \App\Repository\Call\CallRepository::class
        );

        $this->app->bind(
            \App\Services\CommunicationIntegrator\CommunicationIntegratorInterface::class,
            function ($app) {
                return new \App\Services\CommunicationIntegrator\TwilioService(
                    env('TWILIO_SID'),
                    env('TWILIO_AUTH_TOKEN'),
                );
            }
        );

    }
}
