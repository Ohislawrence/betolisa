<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log admin actions (only POST, PUT, PATCH, DELETE)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $user = $request->user();

            if ($user) {
                Log::channel('admin')->info('Admin Action', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'action' => $request->method(),
                    'url' => $request->fullUrl(),
                    'route' => $request->route()?->getName(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'input' => $request->except(['password', 'password_confirmation', '_token']),
                ]);
            }
        }

        return $response;
    }
}
