<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('water_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null'); // derived automatically from hostel
            $table->date('date');
            $table->string('time'); // uses a fixed set of values (e.g., 06:00 PM, 12:00 AM, etc.)
            $table->enum('is_water', ['Yes', 'No']);
            $table->enum('amount', ['Plenty', 'Little'])->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // logged-in user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_monitorings');
    }
};
