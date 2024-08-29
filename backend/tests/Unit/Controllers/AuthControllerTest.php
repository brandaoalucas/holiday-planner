<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $clientRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientRepository = app(ClientRepository::class);
        $this->clientRepository->createPersonalAccessClient(
            1,
            'Personal Access Client for Testing 2',
            env('APP_URL')
        );
    }

    public function testLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password@123'), 
        ]);

        
        $credentials = ['email' => 'user@example.com', 'password' => 'Password@123'];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                    ],
                    'token'
                ]);

        $this->assertEquals($user->email, $response->json('user.email'));
    }

    public function testLoginWithInvalidCredentials()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password@123'),
        ]);

        $credentials = ['email' => 'user@example.com', 'password' => 'wrongpassword'];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(422)
                ->assertJson([
                    'message' => 'Provided email or password is incorrect'
                ]);
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

        $response = $this->postJson('/api/v1/signup', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function testSignupWithWeakPassword()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'weakpass', 
            'password_confirmation' => 'weakpass',
        ];

        $response = $this->postJson('/api/v1/signup', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password']);
    }



}
