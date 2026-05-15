<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function subscriptionSettings()
    {
        $cost = Setting::getValue('subscription_cost', 5000);
        $duration = Setting::getValue('subscription_duration_days', 30);
        $paystackPublicKey = Setting::getValue('paystack_public_key', '');
        $paystackSecretKey = Setting::getValue('paystack_secret_key', '');
        $stats = $this->subscriptionService->getStats();

        return view('admin.settings.subscription', compact(
            'cost', 'duration', 'paystackPublicKey', 'paystackSecretKey', 'stats'
        ));
    }

    public function updateSubscriptionSettings(Request $request)
    {
        $validated = $request->validate([
            'subscription_cost' => 'required|numeric|min:0',
            'subscription_duration_days' => 'required|integer|min:1',
            'paystack_public_key' => 'nullable|string',
            'paystack_secret_key' => 'nullable|string',
        ]);

        Setting::setValue('subscription_cost', $validated['subscription_cost'], 'integer');
        Setting::setValue('subscription_duration_days', $validated['subscription_duration_days'], 'integer');
        Setting::setValue('paystack_public_key', $validated['paystack_public_key'], 'string');
        Setting::setValue('paystack_secret_key', $validated['paystack_secret_key'], 'string');

        return redirect()->back()->with('success', 'Subscription settings updated successfully.');
    }
}
