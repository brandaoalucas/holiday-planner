<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $user, $clientRepository, $personalAccessClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->make([
            'id' => 1,
            'role' => 'admin',
        ]);

        $this->clientRepository = app(ClientRepository::class);

        if (!$this->clientRepository) {
            $this->clientRepository->createPersonalAccessClient(
                null,
                'Personal Access Client for Testing',
                env('APP_URL')
            );
        }

        Passport::actingAs($this->user);
    }

    public function testGetAllReturns204WhenNoUsersExist()
    {
        $response = $this->getJson(route('users.getAll'));
        $response->assertStatus(204);
    }

    public function testGetAllReturns200ArrayOfUsers()
    {
        $users = User::factory()->count(2)->create();
        $response = $this->getJson(route('users.getAll'));
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Users retrieved successfully.',
            'data' => $users->toArray(),
        ]);
    }

    public function testCreateReturns422FailsValidationsIfMissRequiredFields()
    {
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];

        $response = $this->postJson(route('users.create'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function testFindByIdReturns200IfUserFound()
    {
        $user = User::factory()->create(['name' => 'Test Name']);
        $response = $this->getJson(route('users.findById', ['id' => $user->id]));
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User retrieved successfully.',
            'data' => $user->toArray(),
        ]);
    }

    public function testFindByIdReturns404WhenUserDoesNotExist()
    {
        $response = $this->getJson(route('users.findById', ['id' => 999]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdReturns404WhenUserDoesSoftDeteleted()
    {
        $user = User::factory()->create(['deleted_at' => now()->addDay()]);
        $response = $this->getJson(route('users.findById', ['id' => $user->id]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdReturnsRestoredUser()
    {
        $user = User::factory()->create(['deleted_at' => now()->addDay()]);
        $user->restore();
        $response = $this->getJson(route('users.findById', ['id' => $user->id]));
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User retrieved successfully.',
            'data' => $user->toArray(),
        ]);
    }

    public function testFindByIdTrashedReturns200AndUserIfItIsTheAuthenticatedUser()
    {
        $user = User::factory()->create(['deleted_at' => now()->addDay()]);
        $response = $this->getJson(route('users.findByIdTrashed', ['id' => $user->id]));
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User retrieved successfully.',
            'data' => $user->toArray(),
        ]);
    }

    public function testFindByIdTrashedReturns404WhenNoExistsSoftDeleted()
    {
        $response = $this->getJson(route('users.findByIdTrashed', ['id' => 999]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdTrashedReturns404WhenUserIsNotSoftDeleted()
    {
        $user = User::factory()->create();
        $response = $this->getJson(route('users.findByIdTrashed', ['id' => $user->id]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testUpdateReturns204UserIfSuccessfullyUpdate()
    {
        $user = User::factory()->create(['name' => 'Old name']);

        $newData = [
            'name' => 'New name',
            'email' => $user->email,
            'role' => $user->role,
        ];

        $response = $this->putJson(route('users.update', ['id' => $user->id]), $newData);
        $response->assertStatus(204);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $newData['name'], 'email' => $user->email]);
    }

    public function testUpdateUserReturn422ValidationFails()
    {
        $user = User::factory()->create();
        $newData = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];
        $response = $this->putJson(route('users.update', ['id' => $user->id]), $newData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function testUpdateUserReturn404IfUserSoftDeleted()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'deleted_at' => now()->addDay(),
        ]);

        $newData = [
            'name' => 'New Name',
            'email' => $user->email,
            'role' => $user->role,
        ];

        $response = $this->putJson(route('users.update', ['id' => $user->id]), $newData);
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testDeleteReturn204IfUserSuccessfullySoftDeleted()
    {
        $user = User::factory()->create();
        $response = $this->deleteJson(route('users.delete', ['id' => $user->id]));
        $response->assertStatus(204);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function testDeleteReturn404IfUserAlreadySoftDeleted()
    {
        $user = User::factory()->create(['deleted_at' => now()->addDay()]);
        $response = $this->deleteJson(route('users.delete', ['id' => $user->id]));
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testDeleteReturn404IfUserDoesNotExists()
    {
        $response = $this->deleteJson(route('users.delete', ['id' => 999]));
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testRestoreReturns204IfSoftDeletedUserSuccessfullyRestored()
    {
        $user = User::factory()->create(['deleted_at' => now()->addDay()]);

        $this->assertSoftDeleted('users', ['id' => $user->id]);

        $response = $this->postJson(route('users.restore', ['id' => $user->id]));

        $response->assertStatus(204);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    public function testRestoreReturns404IfUserIsNotSoftDeleted()
    {
        $user = User::factory()->create(['id' => $this->user->id]);

        $response = $this->postJson(route('users.restore', ['id' => $user->id]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testRestoreReturns404IfUserDoesNotExists()
    {
        $response = $this->postJson(route('users.restore', ['id' => 999]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
            'data' => [],
        ]);
    }

    public function testGetAllOnlyTrashedReturn200ArrayOfSoftDeletedUsers()
    {
        User::factory()->count(2)->create();
        $deletedUsers = User::factory()->count(2)->create(['deleted_at' => now()->addDay()]);
        $response = $this->getJson(route('users.getAllOnlyTrashed'));
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Soft-deleted users retrieved successfully.',
            'data' => $deletedUsers->toArray(),
        ]);
    }

    public function testGetAllOnlyTrashedReturn204EmptyArrayIfNoUsersWasSoftDeleteed()
    {
        User::factory()->count(2)->create();
        $response = $this->getJson(route('users.getAllOnlyTrashed'));
        $response->assertStatus(204);
    }

    public function testGetAllOnlyTrashedReturn204IfNoHolidayPlansCreated()
    {
        $response = $this->getJson(route('users.getAllOnlyTrashed'));
        $response->assertStatus(204);
    }
}
