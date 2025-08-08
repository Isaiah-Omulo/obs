<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_hostel_summaries', function (Blueprint $table) {
            $table->id();
            // Link to the hostel
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');

            $table->date('date');
            $table->integer('capacity'); // Snapshot of capacity
            $table->integer('students_present_night')->nullable(); // Can be null initially
            $table->integer('students_present_day')->nullable();   // Can be null initially
            $table->timestamps();

            // This ensures you can never have a duplicate entry for the same hostel on the same day.
            $table->unique(['hostel_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_hostel_summaries');
    }
};