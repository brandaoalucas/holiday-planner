<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

use App\Repositories\HolidayPlanRepository;
use App\Repositories\HolidayPlanRepositoryInterface;
use App\Services\HolidayPlanService;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HolidayPlanRepositoryInterface::class, HolidayPlanRepository::class);
        $this->app->singleton(HolidayPlanService::class, function ($app) {
            return new HolidayPlanService($app->make(HolidayPlanRepositoryInterface::class));
        });

        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });

        $this->app->register(
            \L5Swagger\L5SwaggerServiceProvider::class
        );

        $this->app->register(
            \Barryvdh\DomPDF\ServiceProvider::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();
    }
}
