<?php

namespace Tests\Unit;

use App\Jobs\AddToTelegramGroup;
use App\Models\User;
use App\Notifications\PaymentConfirmation;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_subscription_dispatches_job_and_notification(): void
    {
        Bus::fake();
        Notification::fake();

        $user = User::factory()->create();
        $service = new SubscriptionService();

        $subscription = $service->createSubscription(
            $user,
            'sub_ref_001',
            5000.00,
            ['plan' => 'premium']
        );

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'user_id' => $user->id,
            'transaction_ref' => 'sub_ref_001',
            'is_active' => true,
            'status' => 'active',
        ]);

        $this->assertTrue($subscription->isActive());

        Bus::assertDispatched(AddToTelegramGroup::class);

        Notification::assertSentTo([$user], PaymentConfirmation::class);
    }

    public function test_process_successful_payment_creates_subscription_and_links_transaction(): void
    {
        Bus::fake();
        Notification::fake();

        $user = User::factory()->create();
        $service = new SubscriptionService();

        $transaction = $service->createTransaction(
            $user,
            'txn_001',
            5000.00,
            ['channel' => 'card']
        );

        $subscription = $service->processSuccessfulPayment('txn_001', [
            'channel' => 'card',
            'paid_at' => now()->toDateTimeString(),
        ]);

        $this->assertNotNull($subscription);
        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'user_id' => $user->id,
            'transaction_ref' => 'txn_001',
            'status' => 'active',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'successful',
            'subscription_id' => $subscription->id,
        ]);

        $this->assertTrue($user->fresh()->hasActiveSubscription());
        Notification::assertSentTo([$user], PaymentConfirmation::class);
    }
}
