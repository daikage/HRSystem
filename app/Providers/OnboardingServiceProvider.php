<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OnboardingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Core\Onboarding\Interfaces\OnboardingRepositoryInterface::class,
            \App\Core\Onboarding\Repositories\OnboardingRepository::class
        );

        $this->app->bind(
            \App\Core\Onboarding\Interfaces\OnboardingServiceInterface::class,
            \App\Core\Onboarding\Services\OnboardingService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
