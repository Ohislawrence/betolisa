<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    /**
     * Determine if user can view subscriptions
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can view a specific subscription
     */
    public function view(User $user, Subscription $subscription): bool
    {
        // Admin can view all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Bettors can only view their own subscriptions
        return $user->id === $subscription->user_id;
    }

    /**
     * Determine if user can create subscriptions
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can update subscriptions
     */
    public function update(User $user, Subscription $subscription): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can delete subscriptions
     */
    public function delete(User $user, Subscription $subscription): bool
    {
        return $user->hasRole('admin');
    }
}
