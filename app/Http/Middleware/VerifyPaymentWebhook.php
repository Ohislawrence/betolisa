<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPaymentWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        // For Paystack webhook verification
        $signature = $request->header('x-paystack-signature');

        if (!$signature) {
            // If no signature, allow only in local/testing environment
            if (app()->environment('production')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            // Verify Paystack signature
            $secretKey = config('services.paystack.secret_key');
            $payload = $request->getContent();
            $expectedSignature = hash_hmac('sha512', $payload, $secretKey);

            if (!hash_equals($expectedSignature, $signature)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        return $next($request);
    }
}
