<?php

namespace Tests\Unit\Repositories;

use App\Models\HolidayPlan;
use App\Models\User;
use App\Repositories\HolidayPlanRepository;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HolidayPlanRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected HolidayPlanRepository $holidayPlanRepository;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->holidayPlanRepository = app(HolidayPlanRepository::class);
    }

    public function testGetAllReturnsEmptyArrayWhenNoHolidayPlan()
    {
        $result = $this->holidayPlanRepository->getAll();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllReturnsArrayOfHolidayPlans()
    {
        $plans = HolidayPlan::factory()->count(2)->create();
        $result = $this->holidayPlanRepository->getAll();
        $this->assertIsArray($result);
        $this->assertCount(count($plans), $result);
    }

    public function testGetAllByUserIdReturnsEmptyArrayIfNoHolidayPlansCreated()
    {
        $result = $this->holidayPlanRepository->getAllByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllByUserIdReturnsArrayOfHolidayPlansCreatedBySpecifiedUser()
    {
        $user = User::factory()->create();
        HolidayPlan::factory()->count(2)->create(['user_id' => $user->id]);
        $result = $this->holidayPlanRepository->getAllByUserId($this->user->id);

        $this->assertIsArray($result);
        foreach ($result as $plan) {
            $this->assertTrue($plan->user_id === $user->id);
        }
    }

    public function testGetAllByUserIdReturnsOnlyHolidayPlansCreatedBySpecifiedUser()
    {
        HolidayPlan::factory()->count(2)->create();

        $authenticatedUserPlans = HolidayPlan::factory()->count(2)->create(['user_id' => $this->user->id]);

        $result = $this->holidayPlanRepository->getAllByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertCount(count($authenticatedUserPlans), $result);
        foreach ($result as $plan) {
            $this->assertEquals($this->user->id, $plan['user_id']);
        }
    }

    public function testGetAllByUserIdReturnsEmptyIfNoHolidayPlanCreatedBySpecifiedUser()
    {
        HolidayPlan::factory()->count(2)->create();

        $result = $this->holidayPlanRepository->getAllByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function  testFindByIdReturnsNullIfHolidayPlanDoesNotExist()
    {
        $result = $this->holidayPlanRepository->findById(999);
        $this->assertNull($result);
    }

    public function  testFindByIdReturnsNullIfHolidayPlanSoftDeleted()
    {
        $plan = HolidayPlan::factory()->create();
        $planDeleted = $plan->delete();

        $result = $this->holidayPlanRepository->findById($plan->id);

        $this->assertNull($result);
        $this->assertSoftDeleted($plan);
        $this->assertTrue($planDeleted);
    }

    public function testFindByIdReturnsHolidayPlan()
    {
        $plan = HolidayPlan::factory()->create();

        $result = $this->holidayPlanRepository->findById($plan->id);

        $this->assertInstanceOf(HolidayPlan::class, $result);
        $this->assertEquals($plan->id, $result->id);
    }

    public function  testFindByIdTrashedReturnsFalseIfHolidayPlanDoesNotExist()
    {
        $result = $this->holidayPlanRepository->findByIdTrashed(999);
        $this->assertNull($result);
    }

    public function  testFindByIdTrashedReturnsFalseIfNoSoftDeletedHolidayPlanExists()
    {
        $plan = HolidayPlan::factory()->create();

        $result = $this->holidayPlanRepository->findByIdTrashed($plan->id);

        $this->assertNull($result);
    }

    public function  testFindByIdTrashedReturnsHolidayPlanSoftDeleted()
    {
        $plan = HolidayPlan::factory()->create();
        $plan->delete();

        $result = $this->holidayPlanRepository->findByIdTrashed($plan->id);

        $this->assertInstanceOf(HolidayPlan::class, $plan);
        $this->assertSoftDeleted($plan);
    }

    public function testCreateReturnsHolidayPlanOnSuccess()
    {
        $data = HolidayPlan::factory()->make();

        $result = $this->holidayPlanRepository->create($data->toArray());

        $this->assertInstanceOf(HolidayPlan::class, $result);
        $this->assertEquals($data->title, $result->title);
    }

    public function testCreateFailsWhenRequiredFieldsAreMissing()
    {
        $requiredFields = [
            'title' => null,
            'description' => null,
            'location' => null,
            'date' => null
        ];
        $data = HolidayPlan::factory()->make()->toarray();

        foreach ($requiredFields as $field => $value){
            $this->expectException(QueryException::class);
            $data[$field] = $value;
            $result = $this->holidayPlanRepository->create($data);
            $this->assertFalse($result);
        }
    }

    public function testUpdateReturnsTrueOnSuccess()
    {   $user = User::factory()->create();
        $plan = HolidayPlan::factory()->create([
            'title' => 'Original Title',
            'user_id' => $user->id,
        ]);

        $newData = ['title' => 'Updated Title'];

        $result = $this->holidayPlanRepository->update($plan, $newData);

        $this->assertTrue($result);
        $this->assertDatabaseHas('holiday_plans', ['title' => 'Updated Title']);
    }

    public function testUpdateFailsWithInvalidData()
    {
        $plan = HolidayPlan::factory()->create();

        $requiredFields = [
            'title' => null,
            'description' => null,
            'location' => null,
            'date' => null,
        ];

        foreach ($requiredFields as $field => $value) {
            $this->expectException(QueryException::class);
            $result = $this->holidayPlanRepository->update($plan, [$field => $value]);
            $this->assertNull($result);
        }
    }

    public function testSoftDeletesAnHolidayPlan()
    {
        $plan = HolidayPlan::factory()->create();

        $result = $this->holidayPlanRepository->delete($plan);

        $this->assertTrue($result);
        $this->assertSoftDeleted('holiday_plans', ['id' => $plan->id]);
    }

    public function testRestoreReturnsTrueOnSuccess()
    {
        $plan = HolidayPlan::factory()->create();

        $this->holidayPlanRepository->delete($plan);
        $this->assertSoftDeleted($plan);

        $result = $this->holidayPlanRepository->restore($plan);

        $this->assertTrue($result);
        $this->assertDatabaseHas('holiday_plans', ['id' => $plan->id, 'deleted_at' => null]);
    }

    public function testGetAllOnlyTrashedReturnsSoftDeletedPlans()
    {
        HolidayPlan::factory()->count(2)->create()->toArray();
        $trashedPlans = HolidayPlan::factory()->count(2)->create()
            ->each(function ($plan) {
                $plan->delete();
            })->toArray();

        $result = $this->holidayPlanRepository->getAllOnlyTrashed();

        $this->assertEquals(count($trashedPlans), count($result));

        foreach ($result as $trashedPlan) {
            $this->assertNotTrue($trashedPlan['deleted_at'] === null);
        }

    }

    public function testGetAllOnlyTrashedReturnsEmptyArrayWhenNoPlansAreDeleted()
    {
        HolidayPlan::factory()->count(3)->create();

        $result = $this->holidayPlanRepository->getAllOnlyTrashed();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedReturnsEmptyArrayWhenNoPlansCreated()
    {
        $result = $this->holidayPlanRepository->getAllOnlyTrashed();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedByUserIdReturnsEmptyArrayWhenNoPlansCreated()
    {
        $result = $this->holidayPlanRepository->getAllOnlyTrashedByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedByUserIdReturnsEmptyArrayWhenNoPlansAreDeleted()
    {
        HolidayPlan::factory()->count(3)->create();

        $result = $this->holidayPlanRepository->getAllOnlyTrashedByUserId($this->user->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAllOnlyTrashedByUserIdReturnsArraySoftDeletedPlansOfSpecifiedUser()
    {
        HolidayPlan::factory()->count(2)->create();
        $plans = HolidayPlan::factory()->count(3)->create(['user_id' => $this->user->id])
            ->each(function ($plan) {
                $plan->delete();
            })->toArray();

        $result = $this->holidayPlanRepository->getAllOnlyTrashedByUserId($this->user->id);

        $this->assertCount(count($plans), $result);
        foreach ($result as $plan) {
            $this->assertNotTrue($plan['deleted_at'] === null);
        }
    }
}
