<?php

namespace App\Providers;

use App\Models\Tip;
use App\Models\Subscription;
use App\Policies\TipPolicy;
use App\Policies\SubscriptionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\View\Composers\SubscriptionComposer;
use Illuminate\Support\Facades\View;

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
        Gate::policy(Tip::class, TipPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);

        // Additional gates
        Gate::define('access-admin', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('access-premium', function ($user) {
            return $user->hasRole('admin') || $user->hasActiveSubscription();
        });

        Gate::define('manage-subscriptions', function ($user) {
            return $user->hasRole('admin');
        });

        RateLimiter::for('payment', function ($request) {
            return Limit::perMinute(3)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(5)->by($request->input('email').'|'.$request->ip());
        });

        View::composer('*', SubscriptionComposer::class);
    }
}
