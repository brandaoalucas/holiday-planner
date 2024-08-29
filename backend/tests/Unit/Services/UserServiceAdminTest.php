<?php
namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserServiceAdminTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;
    protected User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make([
            'id' => 1,
            'role' => 'admin',
        ]);

        $clientRepository = app(ClientRepository::class);
        $personalAccessClient = $clientRepository->createPersonalAccessClient(
            $this->user->id,
            'Personal Access Client for ' . $this->user->name,
            env('APP_URL')
        );

        Passport::actingAs($this->user);

        $this->user->setRelation('personalAccessClient', $personalAccessClient);

        $this->userService = app(UserService::class);
    }

    public function testGetAllReturnsEmptyArrayIfNoCreatedUsers()
    {
        $result = $this->userService->getAll();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllReturnsArrayOfUsersIfCreatedUsers()
    {
        $users = User::factory()->count(2)->create();
        $result = $this->userService->getAll();

        $this->assertIsArray($result);
        $this->assertCount(count($users), $result);
    }

    public function testFindByIdReturnsUser()
    {
        $user = User::factory()->create();
        $result = $this->userService->findById($user->id);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }

    public function testFindByIdReturnsNullIfUserDoesNotExist()
    {
        $result = $this->userService->findById(999);
        $this->assertNull($result);
    }

    public function testFindByIdReturnsNullIfUserSoftDeleted()
    {
        $user = User::factory()->create();
        $deleted = $user->delete();
        $result = $this->userService->findById($user->id);

        $this->assertSoftDeleted($user);
        $this->assertTrue($deleted);
        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnNullIfUserDoesNotExists()
    {
        $result = $this->userService->findByIdTrashed(999);
        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnSoftDeletedUser()
    {
        $user = User::factory()->create();
        $user->delete();
        $result = $this->userService->findByIdTrashed($user->id);
        $this->assertInstanceOf(User::class, $result);
        $this->assertSoftDeleted($user);
    }

    public function testFindByIdTrashedReturnNullIfNotSoftDeletedUser()
    {
        $user = User::factory()->create();
        $result = $this->userService->findByIdTrashed($user->id);
        $this->assertNull($result);
    }

    public function testFindByEmailReturnNullIfUserDoesNotExists()
    {
        $result = $this->userService->findByEmail('test@fail.com');
        $this->assertNull($result);
    }

    public function testFindByEmailReturnUser()
    {
        $user = User::factory()->create();
        $result = $this->userService->findByEmail($user->email);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->name, $result->name);
    }

    public function testFindByEmailReturnNullIfSoftDeletedUser()
    {
        $user = User::factory()->create();
        $user->delete();
        $result = $this->userService->findByEmail($user->email);
        $this->assertSoftDeleted($user);
        $this->assertNull($result);
    }
    
    public function testCreateReturnFailsUserWhitoutRequiredFields()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        foreach ($data as $index => $value) {
            $this->expectException(QueryException::class);
            $data[$index] = null;
            $result = $this->userService->create($data);
            $this->assertNull($result);
        }
    }

    public function testUpdateReturnFailsWithoutRequiredFields()
    {
        $oldData = [
            'name' => 'Old Name',
            'password' => Hash::make('Password123!'),
        ];
        $fields = [
            'name' => null,
            'email' => null,
            'password' => null,
        ];
        $user = User::factory()->create($oldData);

        foreach ($fields as $field => $value) {
            $this->expectException(QueryException::class);
            $updated = $this->userService->update($user->id, [$field => $value]);
            $this->assertFalse($updated);
        }
    }

    public function testUpdateReturnTrueIfSuccessfullyUpdated()
    {
        $oldData = [
            'name' => 'Old Name',
            'password' => Hash::make('Password123!'),
        ];
        $user = User::factory()->create($oldData);
        $result = $this->userService->update($user->id, ['name' => 'New Name']);
        $this->assertTrue($result);
        $this->assertTrue($user->fresh()->name === 'New Name');
    }

    public function testDeleteReturnTrueIfSuccessfullyDeleted()
    {
        $user = User::factory()->create(['id' => $this->user->id]);
        $result = $this->userService->delete($user->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted($user);
    }

    public function testDeleteReturnFalseIfUserAlreadyDeleted()
    {
        $user = User::factory()->create();
        $user->delete();
        $result = $this->userService->delete($user->id);

        $this->assertFalse($result);
        $this->assertSoftDeleted($user);
    }

    public function testRestoreReturnTrueIfUserSuccessfullyRestored()
    {
        $user = User::factory()->create();
        $user->delete();
        $this->assertSoftDeleted($user);

        $result = $this->userService->restore($user->id);
        $this->assertTrue($result);
        $this->assertTrue($user->fresh()->deleted_at === null);
    }

    public function testRestoreReturnFalseIfUserNotSoftDeleted()
    {
        $user = User::factory()->create();

        $result = $this->userService->restore($user->id);
        $this->assertFalse($result);
    }

    public function testGetAllOnlyTrashedReturnsEmptyArrayWhenNoUsersAreTrashed()
    {
        User::factory()->count(3)->create();

        $result = $this->userService->getAllOnlyTrashed();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedReturnsArrayOfTrashedUsers()
    {
        $users = User::factory()->count(3)->create()->each(
            function ($user) {
                $user->delete();
            })->toArray();

        $result = $this->userService->getAllOnlyTrashed();

        $this->assertCount(count($users), $result);
        $this->assertIsArray($result);
    }
}
