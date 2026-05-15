<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin always has access
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Check if user has active subscription
        if (!$user->hasActiveSubscription()) {
            // If it's an AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Subscription required',
                    'message' => 'You need an active subscription to access premium content.',
                    'redirect' => route('bettor.plans')
                ], 403);
            }

            return redirect()->route('bettor.plans')
                ->with('error', 'You need an active subscription to access premium content.');
        }

        // Check if user account is active
        if (!$user->is_active) {
            auth()->logout();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Account deactivated',
                    'message' => 'Your account has been deactivated. Please contact support.'
                ], 403);
            }

            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated. Please contact support.');
        }

        return $next($request);
    }
}
