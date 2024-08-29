<?php

namespace Tests\Unit\Services;

use App\Models\HolidayPlan;
use App\Models\User;
use App\Repositories\HolidayPlanRepository;
use Tests\TestCase;
use App\Services\HolidayPlanService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Auth\Authenticatable;

class HolidayPlanServiceAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $holidayPlanService, $admin;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Authenticatable $admin */
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);

        $this->holidayPlanService = app(HolidayPlanService::class);
    }

    public function testGetAllReturnsEmptyArrayWhenNoPlansExist()
    {
        $result = $this->holidayPlanService->getAll();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllReturnsArrayOfAllCreatedHolidayPlans()
    {
        $plans = HolidayPlan::factory()->count(2)->create()->toArray();

        $result = $this->holidayPlanService->getAll();

        $totalPlans = count($plans);

        $this->assertIsArray($result);
        $this->assertCount($totalPlans, $result);

        for ($i = 0; $i < $totalPlans; $i++) {
            $this->assertEquals($plans[$i]['id'], $result[$i]['id']);
            $this->assertFalse($result[$i]['user_id'] === $this->admin->id);
        }
    }

    public function testGetAllByUserIdReturnsArrayOfHolidayPlans()
    {
        $user = User::factory()->create();
        $plans = HolidayPlan::factory()->count(2)->create(['user_id' => $user->id])->toArray();
        $result = $this->holidayPlanService->getAllByUserId($user->id);

        $totalPlans = count($plans);

        $this->assertIsArray($result);
        $this->assertCount($totalPlans, $result);

        for ($i = 0; $i < $totalPlans; $i++) {
            $this->assertEquals($plans[$i]['id'], $result[$i]['id']);
        }
    }

    public function testGetAllByUserIdReturnsEmptyArrayWhenNoHolidayPlansCreated()
    {
        $user = User::factory()->create();
        $result = $this->holidayPlanService->getAllByUserId($user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testFindByIdReturnsHolidayPlan()
    {
        $plan = HolidayPlan::factory()->create();

        $result = $this->holidayPlanService->findById($plan->id);

        $this->assertInstanceOf(HolidayPlan::class, $result);
        $this->assertEquals($plan->id, $result->id);
    }

    public function testFindByIdReturnsNullWhenPlanDoesNotExist()
    {
        $result = $this->holidayPlanService->findById(999);

        $this->assertNull($result);
    }

    public function testFindByIdReturnsNullIfPlanWasSoftDeleted()
    {
        $plan = HolidayPlan::factory(['deleted_at' => now()->addDay()])->create();
        $result = $this->holidayPlanService->findById($plan->id);

        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnsNullIfPlanWasNotSoftDeleted()
    {
        $plan = HolidayPlan::factory()->create();
        $result = $this->holidayPlanService->findByIdTrashed($plan->id);

        $this->assertNull($result);
    }

    public function testFindByIdTrashedReturnsHolidayPlanSoftDeleted()
    {
        $plan = HolidayPlan::factory()->create(['deleted_at' => now()->addDay()]);
        $result = $this->holidayPlanService->findByIdTrashed($plan->id);

        $this->assertSoftDeleted($result);
        $this->assertInstanceOf(HolidayPlan::class, $result);
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
        $requiredFields = [
            'title' => null,
            'description' => null,
            'location' => null,
            'date' => null,
        ];

        $data = HolidayPlan::factory()->make()->toArray();
;
        foreach ($requiredFields as $field => $value) {
            $this->expectException(QueryException::class);
            $data[$field] = $value;
            $this->holidayPlanService->create($data);
        }
    }

    public function testUpdateReturnsTrueAndValidateDataOnSuccess()
    {
        $plan = HolidayPlan::factory()->create(['title' => 'Old Title']);

        $result = $this->holidayPlanService->update($plan->id, ['title' => 'New Title']);

        $this->assertTrue($result);

        $this->assertDatabaseHas('holiday_plans', [
            'id' => $plan->id,
            'title' => 'New Title',
        ]);
    }

    public function testUpdateFailsWhenRequiredFieldsAreMissing()
    {
        $plan = HolidayPlan::factory()->create();
        $i = 0;
        $requiredFields = ['title', 'description', 'location', 'date'];
        while ($requiredFields[$i]) {
            $this->expectException(QueryException::class);
            $result = $this->holidayPlanService->update($plan->id, [$requiredFields[$i] => null]);
            $this->assertFalse($result);
            $i++;
        }
    }

    public function testUpdateReturnsFalseWhenPlanDoesNotExist()
    {
        $result = $this->holidayPlanService->update(999, ['title' => 'New Title']);
        $this->assertFalse($result);
    }

    public function testDeleteReturnsTrueOnSuccess()
    {
        $plan = HolidayPlan::factory()->create();

        $result = $this->holidayPlanService->delete($plan->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted($plan);
    }

    public function testDeleteReturnsFalseWhenPlanDoesNotExist()
    {
        $result = $this->holidayPlanService->delete(999);

        $this->assertFalse($result);
    }

    public function testDeleteReturnsFalseWhenSoftDeletedPlan()
    {
        $plan = HolidayPlan::factory()->create(['deleted_at' => now()->addDay()]);
        $result = $this->holidayPlanService->delete($plan->id);

        $this->assertFalse($result);
    }

    public function testRestoreReturnsTrueOnSuccess()
    {
        $plan = HolidayPlan::factory()->create();
        $plan->delete();
        $this->assertSoftDeleted($plan);
        $result = $this->holidayPlanService->restore($plan->id);
        $this->assertTrue($result);
    }

    public function testRestoreReturnsFalseWhenPlanIsNotDeleted()
    {
        $plan = HolidayPlan::factory()->create();
        $result = $this->holidayPlanService->restore($plan->id);
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

    public function testGetAllOnlyTrashedReturnsDeletedPlans()
    {
        HolidayPlan::factory()->count(2)->create();

        $deletedPlans = HolidayPlan::factory()->count(2)->create()->each(function ($plan) {
            $plan->delete();
        })->toArray();

        $result = $this->holidayPlanService->getAllOnlyTrashed();

        $this->assertIsArray($result);
        $this->assertCount(count($deletedPlans), $result);

        foreach ($result as $plan) {
            $this->assertTrue($plan['deleted_at'] !== null);
        }
    }


    public function testGetAllOnlyTrashedByUserIdReturnsEmptyArrayWhenNoDeletedPlans()
    {
        $user = User::factory()->create();
        HolidayPlan::factory()->count(2)->create(['user_id' => $user->id]);
        $result = $this->holidayPlanService->getAllOnlyTrashedByUserId($user->id);

        $this->assertIsArray($result);
        $this->isEmpty($result);
    }

    public function testGetAllOnlyTrashedByUserIdReturnsEmptyArrayWhenNoPlansExist()
    {
        $user = User::factory()->create();
        $result = $this->holidayPlanService->getAllOnlyTrashedByUserId($user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedByUserIdReturnsDeletedPlans()
    {
        $user = User::factory()->create();
        HolidayPlan::factory()->count(2)->create(['user_id' => $user->id]);
        $deletedPlans = HolidayPlan::factory()->count(2)->create([
            'user_id' => $user->id,
            'deleted_at' => now()->addDay(),
        ]);

        $result = $this->holidayPlanService->getAllOnlyTrashedByUserId($user->id);

        $this->assertIsArray($result);
        $this->assertCount(count($deletedPlans->toArray()), $result);

        foreach ($result as $plan) {
            $this->assertTrue($plan['deleted_at'] !== null);
        }
    }

}
