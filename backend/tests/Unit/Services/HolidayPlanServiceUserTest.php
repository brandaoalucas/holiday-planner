<?php

namespace Tests\Unit\Services;

use App\Models\HolidayPlan;
use App\Models\User;
use App\Repositories\HolidayPlanRepository;
use App\Services\HolidayPlanService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class HolidayPlanServiceUserTest extends TestCase
{
    use RefreshDatabase;

    protected $holidayPlanService, $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'id' => 1,
            'role' => 'user',
        ]);

        $clientRepository = app(ClientRepository::class);
        $personalAccessClient = $clientRepository->createPersonalAccessClient(
            $this->user->id,
            'Personal Access Client for ' . $this->user->name,
            env('APP_URL')
        );

        Passport::actingAs($this->user);
        $this->user->setRelation('personalAccessClient', $personalAccessClient);

        $this->holidayPlanService = app(HolidayPlanService::class);
    }

    public function testGetAllReturnsEmptyArrayWhenNoPlansExists()
    {
        $result = $this->holidayPlanService->getAll();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllReturnsEmptyArrayWhenNoHolidayPlansCreatedByAuthenticatedUser()
    {
        HolidayPlan::factory()->count(2)->create()->toArray();

        $result = $this->holidayPlanService->getAll();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllReturnsArrayOfHolidayPlansCreatedByAuthenticatedUser()
    {
        HolidayPlan::factory()->count(2)->create();
        $plans = HolidayPlan::factory()->count(2)->create(['user_id' => $this->user->id]);

        $result = $this->holidayPlanService->getAll();

        $totalPlans = count($plans);

        $this->assertIsArray($result);
        $this->assertCount($totalPlans, $result);

        for ($i = 0; $i < $totalPlans; $i++) {
            $this->assertEquals($plans[$i]['id'], $result[$i]['id']);
            $this->assertTrue($result[$i]['user_id'] === $this->user->id);
        }
    }

    public function testGetAllByUserIdReturnsEmptyArrayIfNoPlansCreated()
    {
        $result = $this->holidayPlanService->getAllByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllByUserIdReturnsEmptyArrayIfNotCreatedByAuthenticatedUserAndNotAdmin()
    {
        HolidayPlan::factory()->count(2)->create();
        $result = $this->holidayPlanService->getAllByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllByUserIdReturnsArrayOfHolidayPlansCreatedByAuthenticatedUserOrAdmin()
    {
        HolidayPlan::factory()->count(2)->create();
        $plans = HolidayPlan::factory()->count(2)->create(['user_id' => $this->user->id]);

        $result = $this->holidayPlanService->getAllByUserId($this->user->id);

        $totalPlans = count($plans);

        $this->assertIsArray($result);
        $this->assertCount($totalPlans, $result);

        for ($i = 0; $i < $totalPlans; $i++) {
            $this->assertEquals($result[$i]['user_id'], $this->user->id);
        }
    }

    public function testFindByIdReturnsNullIfNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create();

        $result = $this->holidayPlanService->findById($plan->id);

        $this->assertNull($result);
    }

    public function testFindByIdReturnsNullWhenPlanDoesNotExist()
    {
        $result = $this->holidayPlanService->findById(999);

        $this->assertNull($result);
    }

    public function testFindByIdReturnsHolidayPlanCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);

        $result = $this->holidayPlanService->findById($plan->id);

        $this->assertInstanceOf(HolidayPlan::class, $result);
        $this->assertEquals($plan->id, $result->id);
        $this->assertEquals($result->user_id, $this->user->id);
    }

    public function testFindByIdTrashedReturnsNullWhenPlanDoesNotExist()
    {
        $result = $this->holidayPlanService->findById(999);

        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnsFalseIfDeletedHolidayPlanWasNotCreatedByTheAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create();
        $plan->delete();

        $this->assertSoftDeleted($plan);

        $result = $this->holidayPlanService->findByIdTrashed($plan->id);

        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnsFalseIfTheHolidayPlanHasNotBeenDeleted()
    {
        $plan = HolidayPlan::factory()->create();

        $result = $this->holidayPlanService->findByIdTrashed($plan->id);

        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnsHolidayPlanIfHasBeenCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $plan->delete();

        $result = $this->holidayPlanService->findByIdTrashed($plan->id);

        $this->assertSoftDeleted($result);
        $this->assertInstanceOf(HolidayPlan::class, $result);
        $this->assertEquals($plan->id, $result->id);
    }

    public function testCreateReturnsHolidayPlanOnSuccess()
    {
        $data = HolidayPlan::factory()->make();

        $result = $this->holidayPlanService->create($data->toArray());

        $this->assertInstanceOf(HolidayPlan::class, $result);
        $this->assertEquals($data->title, $result->title);
    }

    public function testCreateThrowsExceptionIfRequiredFieldIsEmpty()
    {
        $requiredFields = ['title', 'description', 'location', 'date'];
        $data = [
            'title' => 'Valid Plan',
            'description' => 'Description of the new plan',
            'date' => now(),
            'location' => 'Test Location',
            'participants' => ['John Doe', 'Jane Doe'],
        ];

        foreach ($requiredFields as $field) {
            $this->expectException(QueryException::class);
            $data[$field] = null;
            $this->holidayPlanService->create($data);
        }
    }

    public function testUpdateReturnFalseIfHolidayPlanNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create();
        $result = $this->holidayPlanService->update($plan->id, ['title' => 'Old Name']);

        $this->assertFalse($result);
    }

    public function testUpdateReturnsTrueAndValidateDataOnSuccess()
    {
        $plan = HolidayPlan::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Old Title',
        ]);

        $result = $this->holidayPlanService->update($plan->id, ['title' => 'New Title']);

        $this->assertTrue($result);
        $this->assertDatabaseHas('holiday_plans', [
            'id' => $plan->id,
            'title' => 'New Title',
        ]);
    }

    public function testUpdateFailsWhenRequiredFieldsAreMissing()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $i = 0;
        $requiredFields = ['title', 'description', 'location', 'date'];
        while ($requiredFields[$i]) {
            $this->expectException(QueryException::class);
            $result = $this->holidayPlanService->update($plan->id, [$requiredFields[$i] => null]);
            $this->assertFalse($result);
        }
    }

    public function testUpdateReturnsFalseWhenPlanDoesNotExist()
    {
        $result = $this->holidayPlanService->update(999, ['title' => 'New Title']);
        $this->assertFalse($result);
    }

    public function testDeleteReturnsTrueOnSuccess()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);

        $result = $this->holidayPlanService->delete($plan->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted($plan);
    }

    public function testDeleteReturnsFalseWhenHolidayPlanDoesNotExist()
    {
        $result = $this->holidayPlanService->delete(999);

        $this->assertFalse($result);
    }

    public function testDeleteReturnsFalseWhenHolidayPlanNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create();
        $result = $this->holidayPlanService->delete($plan->id);

        $this->assertFalse($result);
    }

    public function testRestoreReturnsTrueOnSuccess()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $plan->delete();
        $this->assertSoftDeleted($plan);
        $result = $this->holidayPlanService->restore($plan->id);
        $this->assertTrue($result);
        $this->assertTrue($plan->fresh()->deleted_at === null);
    }

    public function testRestoreReturnsFalseWhenPlanIsNotDeleted()
    {
        $plan = HolidayPlan::factory()->create();
        $result = $this->holidayPlanService->restore($plan->id);
        $this->assertFalse($result);
    }

    public function testRestoreReturnsFalseWhenHolidayPlanNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create();
        $plan->delete();
        $result = $this->holidayPlanService->restore($plan->id);

        $this->assertSoftDeleted($plan);
        $this->assertFalse($result);
    }
    public function testRestoreReturnsFalseWhenPlanDoesNotExist()
    {
        $result = $this->holidayPlanService->restore(999);
        $this->assertFalse($result);
    }

    public function testGetAllOnlyTrashedReturnsEmptyArrayWhenNoDeletedPlans()
    {
        HolidayPlan::factory()->count(2)->create();
        $result = $this->holidayPlanService->getAllOnlyTrashed();

        $this->assertIsArray($result);
        $this->isEmpty($result);
    }

    public function testGetAllOnlyTrashedReturnsEmptyArrayWhenNoPlansExist()
    {
        $result = $this->holidayPlanService->getAllOnlyTrashed();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedReturnsAnEmptyArrayIfAuthenticatedUserIsNotAdmin()
    {
        HolidayPlan::factory()->count(2)->create()->each(
            function ($plan) {
                $plan->delete();
                $this->assertSoftDeleted($plan);
            });

        $result = $this->holidayPlanService->getAllOnlyTrashed();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedByUserIdReturnsEmptyArrayWhenNoCreatedPlans()
    {
        $result = $this->holidayPlanService->getAllOnlyTrashedByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedByUserIdReturnsDeletedPlansCreatedByAuthenticatedUser()
    {
        HolidayPlan::factory()->count(2)->create(['deleted_at' => now()->addDay()]);

        $deletedPlans = HolidayPlan::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'deleted_at' => now()->addDay()
            ])->toArray();

        $result = $this->holidayPlanService->getAllOnlyTrashedByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertCount(count($deletedPlans), $result);

        foreach ($result as $plan) {
            $this->assertTrue($plan['deleted_at'] !== null);
        }
    }


}
