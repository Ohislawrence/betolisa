<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected string $secretKey;
    protected string $publicKey;
    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = Setting::getValue('paystack_secret_key', config('services.paystack.secret_key'));
        $this->publicKey = Setting::getValue('paystack_public_key', config('services.paystack.public_key'));
    }

    /**
     * Initialize a transaction
     */
    public function initializeTransaction(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transaction/initialize', [
                'email' => $data['email'],
                'amount' => $data['amount'] * 100, // Convert to kobo
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'metadata' => $data['metadata'] ?? [],
                'currency' => $data['currency'] ?? 'NGN',
            ]);

            $responseData = $response->json();

            if (!$response->successful() || !$responseData['status']) {
                Log::error('Paystack Initialize Error', [
                    'response' => $responseData,
                    'data' => $data
                ]);
                return [
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Failed to initialize transaction',
                ];
            }

            return [
                'success' => true,
                'data' => $responseData['data'],
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Initialize Exception', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return [
                'success' => false,
                'message' => 'An error occurred while initializing payment',
            ];
        }
    }

    /**
     * Verify a transaction
     */
    public function verifyTransaction(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/transaction/verify/' . $reference);

            $responseData = $response->json();

            if (!$response->successful() || !$responseData['status']) {
                Log::error('Paystack Verify Error', [
                    'response' => $responseData,
                    'reference' => $reference
                ]);
                return [
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Failed to verify transaction',
                ];
            }

            return [
                'success' => true,
                'data' => $responseData['data'],
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Verify Exception', [
                'error' => $e->getMessage(),
                'reference' => $reference
            ]);
            return [
                'success' => false,
                'message' => 'An error occurred while verifying payment',
            ];
        }
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
