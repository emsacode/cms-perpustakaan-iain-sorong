<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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
        // Model Observers
        \App\Models\Page::observe(\App\Observers\PageObserver::class);
        \App\Models\Faq::observe(\App\Observers\FaqObserver::class);

        // General API Rate Limiter (100 requests/minute)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });

        // Form Submissions Rate Limiter (5 requests/minute)
        RateLimiter::for('form-submissions', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
