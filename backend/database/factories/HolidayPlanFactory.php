<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HolidayPlan>
 */
class HolidayPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    // protected $model = HolidayPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3, true),
            'description' => $this->faker->text(30),
            'date' => now()->addDays(1),
            'location' => fake()->city(),
            'participants' => fake()->words(3),
        ];
    }
}
