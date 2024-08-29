<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HolidayPlan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@holidayplanner.com',
            'role' => 'admin',
            'password' => Hash::make('password@123'),
        ]);
        User::factory()->count(20)->create()->each(
            function($user) {
                HolidayPlan::factory()->create(['user_id' => $user->id]);
            });
    }
}
