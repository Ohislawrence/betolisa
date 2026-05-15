<?php

namespace App\Policies;

use App\Models\Tip;
use App\Models\User;

class TipPolicy
{
    /**
     * Determine if user can view any tips
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view tips list
    }

    /**
     * Determine if user can view a specific tip
     */
    public function view(User $user, Tip $tip): bool
    {
        // Admin can view all tips
        if ($user->hasRole('admin')) {
            return true;
        }

        // Free tips are visible to all authenticated users
        if ($tip->type === 'free') {
            return true;
        }

        // Premium tips require active subscription
        return $user->hasActiveSubscription();
    }

    /**
     * Determine if user can create tips
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can update tips
     */
    public function update(User $user, Tip $tip): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can delete tips
     */
    public function delete(User $user, Tip $tip): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can restore tips
     */
    public function restore(User $user, Tip $tip): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can force delete tips
     */
    public function forceDelete(User $user, Tip $tip): bool
    {
        return $user->hasRole('admin');
    }
}
