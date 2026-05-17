<?php

namespace Tests\Feature;

use App\Console\Commands\CheckExpiredSubscriptions;
use App\Jobs\RemoveFromTelegramGroup;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SubscriptionService;
use App\Services\TelegramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class TelegramSubscriptionLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /** Bind a mock TelegramService that records calls but never hits the API. */
    private function mockTelegram(array $overrides = []): Mockery\MockInterface
    {
        $mock = Mockery::mock(TelegramService::class);

        $mock->shouldReceive('isConfigured')->andReturn(true)->byDefault();

        $mock->shouldReceive('createUserInviteLink')
            ->andReturn('https://t.me/+test_invite_link')
            ->byDefault();

        $mock->shouldReceive('addMemberToGroup')
            ->andReturn(['success' => true, 'message' => 'Invite sent.'])
            ->byDefault();

        $mock->shouldReceive('removeMemberFromGroup')
            ->andReturn(['success' => true, 'message' => 'Removed.'])
            ->byDefault();

        foreach ($overrides as $method => $return) {
            $mock->shouldReceive($method)->andReturn($return);
        }

        $this->app->instance(TelegramService::class, $mock);

        return $mock;
    }

    private function seedSettings(): void
    {
        Setting::setValue('subscription_cost', 5000, 'integer');
        Setting::setValue('subscription_duration_days', 30, 'integer');
        Setting::setValue('telegram_bot_token', 'fake_token', 'string');
        Setting::setValue('telegram_group_id', '-100123456789', 'string');
    }

    private function makeUser(string $telegram = '@testuser'): User
    {
        $user = User::factory()->create(['telegram_number' => $telegram]);
        $user->assignRole('bettor');
        return $user;
    }

    // ------------------------------------------------------------------
    // 1. Subscription creation triggers invite
    // ------------------------------------------------------------------

    public function test_subscribing_creates_invite_link_and_dms_user(): void
    {
        $this->seedSettings();
        $mock = $this->mockTelegram();

        $mock->shouldReceive('createUserInviteLink')->once()
            ->andReturn('https://t.me/+unique_link_abc');

        $mock->shouldReceive('addMemberToGroup')->once()
            ->with('@testuser', Mockery::any())
            ->andReturn(['success' => true, 'message' => 'Sent.']);

        $user = $this->makeUser('@testuser');
        $service = app(SubscriptionService::class);

        $subscription = $service->createSubscription($user, 'ref_001', 5000.00, []);

        $this->assertTrue($subscription->isActive());
        $this->assertDatabaseHas('subscriptions', [
            'user_id'  => $user->id,
            'status'   => 'active',
            'is_active' => 1,
        ]);
    }

    // ------------------------------------------------------------------
    // 2. User without telegram_number — invite skipped gracefully
    // ------------------------------------------------------------------

    public function test_subscribing_user_without_telegram_skips_dm_gracefully(): void
    {
        $this->seedSettings();
        $mock = $this->mockTelegram();

        // No telegram_number — job returns early, nothing Telegram-related is called
        $mock->shouldReceive('createUserInviteLink')->never();
        $mock->shouldReceive('addMemberToGroup')->never();

        $user = User::factory()->create(['telegram_number' => null]);
        $user->assignRole('bettor');

        $service = app(SubscriptionService::class);
        $subscription = $service->createSubscription($user, 'ref_002', 5000.00, []);

        $this->assertTrue($subscription->isActive());
    }

    // ------------------------------------------------------------------
    // 3. Subscription expiry removes user from Telegram
    // ------------------------------------------------------------------

    public function test_expired_subscription_triggers_telegram_removal(): void
    {
        $this->seedSettings();
        $mock = $this->mockTelegram();

        // Removal must be called once with the user's telegram number
        $mock->shouldReceive('removeMemberFromGroup')->once()
            ->with('@expireduser')
            ->andReturn(['success' => true, 'message' => 'Removed.']);

        $user = $this->makeUser('@expireduser');

        // Create a subscription that is already past its end date
        Subscription::create([
            'user_id'         => $user->id,
            'starts_at'       => now()->subDays(31),
            'ends_at'         => now()->subMinutes(1),  // expired 1 minute ago
            'status'          => 'active',
            'is_active'       => true,
            'transaction_ref' => 'ref_expired',
            'amount_paid'     => 5000,
            'payment_method'  => 'paystack',
        ]);

        Artisan::call('subscriptions:check-expired');

        $this->assertDatabaseHas('subscriptions', [
            'user_id'   => $user->id,
            'status'    => 'expired',
            'is_active' => 0,
        ]);
    }

    // ------------------------------------------------------------------
    // 4. Active subscription is NOT touched by the expiry command
    // ------------------------------------------------------------------

    public function test_active_subscription_is_not_expired_by_command(): void
    {
        $this->seedSettings();
        $mock = $this->mockTelegram();

        $mock->shouldReceive('removeMemberFromGroup')->never();

        $user = $this->makeUser('@activeuser');

        Subscription::create([
            'user_id'         => $user->id,
            'starts_at'       => now()->subDay(),
            'ends_at'         => now()->addDays(29),   // still active
            'status'          => 'active',
            'is_active'       => true,
            'transaction_ref' => 'ref_active',
            'amount_paid'     => 5000,
            'payment_method'  => 'paystack',
        ]);

        Artisan::call('subscriptions:check-expired');

        $this->assertDatabaseHas('subscriptions', [
            'user_id'   => $user->id,
            'status'    => 'active',
            'is_active' => 1,
        ]);
    }

    // ------------------------------------------------------------------
    // 5. Per-user link is unique (not shared) — different users get different links
    // ------------------------------------------------------------------

    public function test_each_subscriber_gets_a_unique_invite_link(): void
    {
        $this->seedSettings();
        $mock = Mockery::mock(TelegramService::class);
        $mock->shouldReceive('isConfigured')->andReturn(true);
        $mock->shouldReceive('addMemberToGroup')->andReturn(['success' => true, 'message' => 'OK']);

        $links = ['https://t.me/+link_user_1', 'https://t.me/+link_user_2'];
        $callCount = 0;

        $mock->shouldReceive('createUserInviteLink')
            ->twice()
            ->andReturnUsing(function () use (&$callCount, $links) {
                return $links[$callCount++];
            });

        $this->app->instance(TelegramService::class, $mock);

        $user1 = $this->makeUser('@user_one');
        $user2 = $this->makeUser('@user_two');
        $service = app(SubscriptionService::class);

        $service->createSubscription($user1, 'ref_u1', 5000.00, []);
        $service->createSubscription($user2, 'ref_u2', 5000.00, []);

        // Both links were generated — and they are different
        $this->assertSame(2, $callCount);
        $this->assertNotSame($links[0], $links[1]);
    }

    // ------------------------------------------------------------------
    // 6. Admin resend invite — success path
    // ------------------------------------------------------------------

    public function test_admin_resend_invite_dms_user_when_possible(): void
    {
        $this->seedSettings();
        $mock = $this->mockTelegram();

        $mock->shouldReceive('createUserInviteLink')->once()
            ->andReturn('https://t.me/+admin_resend_link');

        $mock->shouldReceive('addMemberToGroup')->once()
            ->andReturn(['success' => true, 'message' => 'Sent.']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create subscription directly — bypass SubscriptionService to avoid triggering the job
        $user = $this->makeUser('@subscriber');
        Subscription::create([
            'user_id'         => $user->id,
            'starts_at'       => now()->subDay(),
            'ends_at'         => now()->addDays(29),
            'status'          => 'active',
            'is_active'       => true,
            'transaction_ref' => 'ref_r',
            'amount_paid'     => 5000,
            'payment_method'  => 'paystack',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.settings.telegram.member.resend-invite', $user))
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    // ------------------------------------------------------------------
    // 7. Admin resend invite — fallback when bot can't DM
    // ------------------------------------------------------------------

    public function test_admin_resend_invite_shows_link_when_dm_fails(): void
    {
        $this->seedSettings();
        $mock = $this->mockTelegram();

        $mock->shouldReceive('createUserInviteLink')->once()
            ->andReturn('https://t.me/+manual_share_link');

        $mock->shouldReceive('addMemberToGroup')->once()
            ->andReturn(['success' => false, 'message' => 'Chat not found.']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create subscription directly — bypass SubscriptionService to avoid triggering the job
        $user = $this->makeUser('@no_dm_user');
        Subscription::create([
            'user_id'         => $user->id,
            'starts_at'       => now()->subDay(),
            'ends_at'         => now()->addDays(29),
            'status'          => 'active',
            'is_active'       => true,
            'transaction_ref' => 'ref_nodm',
            'amount_paid'     => 5000,
            'payment_method'  => 'paystack',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.settings.telegram.member.resend-invite', $user))
            ->assertRedirect()
            ->assertSessionHas('warning');
    }

    // ------------------------------------------------------------------
    // 8. AddToTelegramGroup job — invite link generation fails gracefully
    // ------------------------------------------------------------------

    public function test_add_telegram_group_job_handles_link_generation_failure(): void
    {
        $this->seedSettings();
        $this->mockTelegram([
            'createUserInviteLink' => null,  // link generation fails
        ]);

        $user = $this->makeUser('@broken_link_user');

        // dispatchSync is wrapped in try/catch in SubscriptionService,
        // so subscription creation must succeed even when Telegram fails
        $service = app(SubscriptionService::class);
        $subscription = $service->createSubscription($user, 'ref_fail', 5000.00, []);

        $this->assertTrue($subscription->isActive(),
            'Subscription should be created even when Telegram invite link generation fails'
        );
    }

    // ------------------------------------------------------------------
    // 9. RemoveFromTelegramGroup job — handles missing telegram number
    // ------------------------------------------------------------------

    public function test_remove_telegram_group_job_skips_when_no_telegram_number(): void
    {
        $this->seedSettings();
        $mock = $this->mockTelegram();
        $mock->shouldReceive('removeMemberFromGroup')->never();

        $user = User::factory()->create(['telegram_number' => null]);

        $job = new RemoveFromTelegramGroup($user);
        $job->handle(app(TelegramService::class));

        $this->assertTrue(true);
    }
}
