<?php

namespace App\Providers;

use App\Interfaces\UserInterface;
use App\Services\UserService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // No need to bind services here
        $this->configureRateLimiting();
    }
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
