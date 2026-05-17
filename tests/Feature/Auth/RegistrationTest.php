<?php

namespace Tests\Feature\Auth;

use App\Services\TelegramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();

        // Prevent real Telegram API calls during registration
        $mock = Mockery::mock(TelegramService::class);
        $mock->shouldReceive('isConfigured')->andReturn(false)->byDefault();
        $mock->shouldReceive('createUserInviteLink')->andReturn(null)->byDefault();
        $mock->shouldReceive('addMemberToGroup')->andReturn(['success' => false, 'message' => 'Not configured'])->byDefault();
        $this->app->instance(TelegramService::class, $mock);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'telegram_number' => '@testuser',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('bettor.dashboard', absolute: false));
    }
}
