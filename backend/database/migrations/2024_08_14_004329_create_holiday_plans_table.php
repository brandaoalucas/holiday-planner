<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method defines the structure of the 'holiday_plans' table in the database.
     * It includes fields for storing the title, description, date, location, participants, timestamps, and soft deletes.
     */
    public function up(): void
    {
        Schema::create('holiday_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('date');
            $table->string('location');
            $table->json('participants')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method rolls back the migration by dropping the 'holiday_plans' table.
     */
    public function down(): void
    {
        Schema::dropIfExists('holiday_plans');
    }
};
