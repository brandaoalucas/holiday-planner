<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $clientRepository;
    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientRepository = app(ClientRepository::class);
        $this->clientRepository->createPersonalAccessClient(
            1,
            'Personal Access Client for Testing',
            env('APP_URL')
        );
        $this->authService = app(AuthService::class);
    }

    public function testLoginWithInvalidPassword()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = ['email' => 'user@example.com', 'password' => 'wrongpassword'];

        $result = $this->authService->login($credentials);

        $this->assertNull($result);
    }

    public function testLoginWithNonExistentUser()
    {
        $credentials = ['email' => 'nonexistent@example.com', 'password' => 'password123'];

        $result = $this->authService->login($credentials);

        $this->assertNull($result);
    }

    public function testLoginWithEmptyPassword()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = ['email' => 'user@example.com', 'password' => ''];

        $result = $this->authService->login($credentials);

        $this->assertNull($result);
    }

    public function testLoginWithInvalidEmailFormat()
    {
        $credentials = ['email' => 'not-an-email', 'password' => 'password123'];

        $result = $this->authService->login($credentials);

        $this->assertNull($result);
    }

    public function testSignupWithDuplicateEmail()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $data = [
            'name' => 'Jane Doe',
            'email' => 'john@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ];

        $this->expectException(\Illuminate\Database\QueryException::class);

        $this->authService->signup($data);
    }
}