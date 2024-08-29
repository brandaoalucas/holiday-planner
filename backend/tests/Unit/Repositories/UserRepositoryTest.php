<?php
namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository(new User);
    }

    public function testGetAllReturnsEmptyArrayWhenNoUsersExist()
    {
        $result = $this->userRepository->getAll();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllReturnsArrayOfUsers()
    {
        $users = User::factory()->count(2)->create();
        $result = $this->userRepository->getAll();
        $this->assertIsArray($result);
        $this->assertCount(count($users), $result);
    }

    public function testFindByIdReturnsUserIfExists()
    {
        $user = User::factory()->create();
        $result = $this->userRepository->findById($user->id);
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }

    public function testFindByIdReturnsNullIfUserSoftDeleted()
    {
        $user = User::factory()->create();
        $deleted = $user->delete();
        $result = $this->userRepository->findById($user->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertNull($result);
    }

    public function testFindByIdReturnsNullIfUserDoesNotExists()
    {
        $result = $this->userRepository->findById(999);
        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnsNullIfUserDoesNotExists()
    {
        $result = $this->userRepository->findByIdTrashed(999);
        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnsNullIfUserNotSoftDeleted()
    {
        $user = User::factory()->create();
        $result = $this->userRepository->findByIdTrashed($user->id);
        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnsUserIfSoftDeleted()
    {
        $user = User::factory()->create(['deleted_at' => now()->addDay()]);
        $result = $this->userRepository->findByIdTrashed($user->id);
        $this->assertInstanceOf(User::class, $result);
        $this->assertSoftDeleted($user);
    }

    public function testFindByEmailReturnsNullIfUserDoesNotExists()
    {
        $result = $this->userRepository->findByEmail('email@fail.com');
        $this->assertNull($result);
    }

    public function testFindByEmailReturnsNullIfUserSoftDeleted()
    {
        $user = User::factory()->create(['deleted_at' => now()->addDay()]);
        $result = $this->userRepository->findByEmail($user->email);
        $this->assertNull($result);
    }

    public function testFindByEmailReturnsUser()
    {
        $user = User::factory()->create();
        $result = $this->userRepository->findByEmail($user->email);
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }

    public function testCreateUser()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
        ];

        $result = $this->userRepository->create($userData);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($result->email, $userData['email']);
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    public function testCannotCreateUserWithExistingEmail()
    {
        User::factory()->create(['email' => 'existing@example.com']);
        $userData = ['email' => 'existing@example.com'];

        $this->expectException(QueryException::class);
        $this->userRepository->create($userData);
    }

    public function testCreateUserFailsWithoutRequiredFields()
    {
        $userData = User::factory()->make()->toArray();
        $requiredFields = ['name', 'email', 'password'];
        $i = 0;
        while($requiredFields[$i]) {
            $userData[$requiredFields[$i]] = null;
            $i++;
            $this->expectException(QueryException::class);
            $this->userRepository->create($userData);
        }
    }

    public function testCannotUpdateUserWithExistingEmail()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $this->expectException(QueryException::class);
        $this->userRepository->update($user2, ['email' => $user1->email]);
    }

    public function testCanUpdateUserWithoutChangingEmail()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'name' => 'Old Name',
        ]);

        $updated = $this->userRepository->update($user, ['name' => 'New Name']);
        $this->assertTrue($updated);

        $this->assertDatabaseHas('users', ['id' => $user->id,'name' => 'New Name']);
    }

    public function testCantUpdateUserWithoutRequiredFields()
    {
        $user = User::factory()->create();
        $requiredFields = ['name', 'email', 'password'];

        foreach($requiredFields as $field) {
            $this->expectException(QueryException::class);
            $updated = $this->userRepository->update($user, [$field => null]);
            $this->assertNull($updated);
        }

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function testUserSoftDelete()
    {
        $user = User::factory()->create();
        $userDeleted = $this->userRepository->delete($user);
        $this->assertTrue($userDeleted);
        $this->assertSoftDeleted($user);
    }

    public function testRestoreSoftDeletedUser()
    {
        $user = User::factory()->create();
        $user->delete();
        $restored = $this->userRepository->restore($user);

        $this->assertTrue($restored);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    public function testGetAllOnlyTrashedReturnsEmptyArrayWhenNoUsersCreated()
    {
        $trashedUsers = $this->userRepository->getAllOnlyTrashed();

        $this->assertIsArray($trashedUsers);
        $this->assertEmpty($trashedUsers);
    }

    public function testGetAllOnlyTrashedReturnsEmptyArrayWhenNoUsersAreTrashed()
    {
        User::factory()->count(2)->create();
        $trashedUsers = $this->userRepository->getAllOnlyTrashed();

        $this->assertIsArray($trashedUsers);
        $this->assertEmpty($trashedUsers);
    }

    public function testGetAllOnlyTrashedReturnsArrayOfDeletedUsers()
    {
        User::factory()->count(3)->create(); // Usuários ativos
        $usersTrashed = User::factory()->count(2)->create()
        ->each(function ($user) {
            $user->delete();
        })->toArray();

        $result = $this->userRepository->getAllOnlyTrashed();

        $this->assertCount(count($usersTrashed), $result);

        foreach ($result as $trashed) {
            $this->assertNotNull($trashed["deleted_at"]);
        }
    }
}
