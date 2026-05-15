<?php

namespace App\View\Composers;

use Illuminate\View\View;

class SubscriptionComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        $view->with('hasSubscription', $user && $user->hasActiveSubscription());
        $view->with('isAdmin', $user && $user->hasRole('admin'));
        $view->with('canAccessPremium', $user && ($user->hasRole('admin') || $user->hasActiveSubscription()));
    }
}
