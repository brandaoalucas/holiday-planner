<?php

namespace Tests\Unit\Controllers;

use App\Models\HolidayPlan;
use App\Models\User;
use App\Services\HolidayPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class HolidayPlanControllerUserTest extends TestCase
{
    protected HolidayPlanService $holidayPlanService;
    protected User $user;

    use RefreshDatabase;

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
    }

    public function testGetAllReturns204WhenNoHolidayPlansExist()
    {
        $response = $this->getJson(route('holiday-plans.getAll'));
        $response->assertStatus(204);
    }

    public function testGetAllReturnsHolidayPlansCreatedByAuthenticatedUser()
    {
        HolidayPlan::factory()->count(2)->create();
        $plans = HolidayPlan::factory()->count(2)->create(['user_id' => $this->user->id])->toArray();
        $response = $this->getJson(route('holiday-plans.getAll'));

        $this->count(count($plans), $response->json()['data']);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Holiday plans retrieved successfully.',
            'data' => $plans,
        ]);
    }

    public function testCreateHolidayPlanSuccessfully()
    {
        $plan = HolidayPlan::factory()->make(['user_id' => $this->user->id])->toArray();
        $response = $this->postJson(route('holiday-plans.create'), $plan);
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Holiday plan created successfully.',
            'data' => $plan,
        ]);
    }

    public function testCreateHolidayPlanValidationFails()
    {
        $data = [
            'title' => '',
            'description' => '',
            'date' => 'invalid-date',
            'location' => '',
            'participants' => [],
        ];

        $response = $this->postJson(route('holiday-plans.create'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'date', 'description', 'location']);
    }

    public function testFindByIdReturnsHolidayPlanCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $response = $this->getJson(route('holiday-plans.findById', ['id' => $plan->id]));
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Holiday plan retrieved successfully.',
            'data' => $plan->toArray(),
        ]);
    }

    public function testFindByIdReturns404IfHolidayPlanWasNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create();
        $response = $this->getJson(route('holiday-plans.findById', ['id' => $plan->id]));
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Holiday plan not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdReturns404WhenHolidayPlanDoesNotExist()
    {
        $response = $this->getJson(route('holiday-plans.findById', ['id' => 999]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Holiday plan not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdReturns404WhenHolidayPlanDoesSoftDeteleted()
    {
        $plan = HolidayPlan::factory()->create([
            'user_id' => $this->user->id,
            'deleted_at' => now()->addDay()
        ]);

        $response = $this->getJson(route('holiday-plans.findById', ['id' => $plan->id]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Holiday plan not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdReturnsRestoredHolidayPlan()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $this->assertTrue($plan->delete());
        $plan->restore();
        $response = $this->getJson(route('holiday-plans.findById', ['id' => $plan->id]));
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Holiday plan retrieved successfully.',
            'data' => $plan->toArray(),
        ]);
    }

    public function testFindByIdTrashedReturnsSoftDeletedHolidayPlanCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create([
            'user_id' => $this->user->id,
            'deleted_at' => now()->addDay(),
        ]);
        $response = $this->getJson(route('holiday-plans.findByIdTrashed', ['id' => $plan->id]));
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Trashed holiday plan retrieved successfully.',
            'data' => $plan->toArray(),
        ]);
    }

    public function testFindByIdTrashedReturns404IfSoftDeletedHolidayPlanWasNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create(['deleted_at' => now()->addDay()]);
        $response = $this->getJson(route('holiday-plans.findByIdTrashed', ['id' => $plan->id]));
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Trashed holiday plan not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdTrashedReturns404WhenHolidayPlanDoesNotExist()
    {
        $response = $this->getJson(route('holiday-plans.findByIdTrashed', ['id' => 999]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Trashed holiday plan not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdTrashedReturns404WhenHolidayPlanWasNotSoftDeleted()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $response = $this->getJson(route('holiday-plans.findByIdTrashed', ['id' => $plan->id]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Trashed holiday plan not found.',
            'data' => [],
        ]);
    }

    public function testFindByIdTrashedReturns404WhenSoftDeletedHolidayPlanWasRestored()
    {
        $plan = HolidayPlan::factory()->create([
            'user_id' => $this->user->id,
            'deleted_at' => now()->addDay(),
        ]);

        $this->assertTrue($plan->restore());

        $response = $this->getJson(route('holiday-plans.findByIdTrashed', ['id' => $plan->id]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Trashed holiday plan not found.',
            'data' => [],
        ]);
    }

    public function testUpdateHolidayPlanReturns204WhenSuccessfully()
    {
        $plan = HolidayPlan::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Old Title'
        ]);
        
        $newData = [
            'title' => 'New Title',
            'description' => $plan->description,
            'date' => $plan->date,
            'location' => $plan->location,
        ];

        $response = $this->putJson(route('holiday-plans.update', ['id' => $plan->id]), $newData);
        $response->assertStatus(204);
        $this->assertDatabaseHas('holiday_plans', ['id' => $plan->id, 'title' => $newData['title']]);
    }

    public function testUpdateHolidayPlanValidationReturns422FailsWhitoutRequiredFields()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $newData = [
            'user_id' => $plan->user_id,
            'title' => '',
            'description' => '',
            'date' => 'invalid-date',
            'location' => '',
            'participants' => [],
        ];
        $response = $this->putJson(route('holiday-plans.update', ['id' => $plan->id]), $newData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description', 'date', 'location']);
    }

    public function testUpdateHolidayPlanValidationFailsWhenHolidayPlanWasNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create();
        $newData = [
            'title' => 'New Title',
            'description' => $plan->description,
            'date' => $plan->date,
            'location' => $plan->location
        ];

        $response = $this->putJson(route('holiday-plans.update', ['id' => $plan->id]), $newData);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Holiday plan not found.',
            'data' => [],
        ]);
    }

    public function testSoftDeleteSuccessfullyWhenHolidayPlanCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $response = $this->deleteJson(route('holiday-plans.delete', ['id' => $plan->id]));
        $response->assertStatus(204);
        $this->assertSoftDeleted('holiday_plans', ['id' => $plan->id]);
    }

    public function testSoftDeleteFailWhenHolidayPlanWasNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create(['user_id' => $this->user->id]);
        $response = $this->deleteJson(route('holiday-plans.delete', ['id' => $plan->id]));
        $response->assertStatus(204);
        $this->assertSoftDeleted('holiday_plans', ['id' => $plan->id]);
    }

    public function testRestoreSuccessfullyWhenHolidayPlanCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create([
            'user_id' => $this->user->id,
            'deleted_at' => now()->addDay(),
        ]);

        $response = $this->postJson(route('holiday-plans.restore', ['id' => $plan->id]));

        $response->assertStatus(204);

        $this->assertDatabaseHas('holiday_plans', [
            'id' => $plan->id,
            'deleted_at' => null,
        ]);
    }

    public function testRestoreFailWhenHolidayPlanWasNotCreatedByAuthenticatedUser()
    {
        $plan = HolidayPlan::factory()->create(['deleted_at' => now()->addDay()]);

        $response = $this->postJson(route('holiday-plans.restore', ['id' => $plan->id]));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'No trashed holiday plan found.',
            'data' => [],
        ]);
    }

    public function testGetAllOnlyTrashedSuccessfullyRetrievedOnlyHolidayPlansCreatedByAuthenticatedUser()
    {
        HolidayPlan::factory()->count(2)->create(['deleted_at' => now()->addDay()]);
        $trashedPlans = HolidayPlan::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'deleted_at' => now()->addDay(),
        ]);
        $response = $this->getJson(route('holiday-plans.getAllOnlyTrashed'));

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Trashed holiday plans retrieved successfully.',
            'data' => $trashedPlans->toArray(),
        ]);
    }

    public function testGetAllOnlyTrashedRetrievedOnlySoftDeletedHolidayPlans()
    {
        HolidayPlan::factory()->count(3)->create(['user_id' => $this->user->id]);
        $response = $this->getJson(route('holiday-plans.getAllOnlyTrashed'));

        $response->assertStatus(204);
    }
}
