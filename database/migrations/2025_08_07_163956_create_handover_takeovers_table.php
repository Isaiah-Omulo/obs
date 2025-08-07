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
        Schema::create('handover_takeovers', function (Blueprint $table) {
            $table->id();
            $table->enum('changeover_type', ['take-over', 'hand-over']);
            
            // Foreign key for the user performing the action
            $table->foreignId('acting_user_id')->constrained('users')->onDelete('cascade');
            
            // Foreign key for the user involved in the action
            $table->foreignId('involved_user_id')->constrained('users')->onDelete('cascade');
            
            $table->foreignId('hostel_id')->constrained('hostels')->onDelete('cascade');
            
            $table->enum('shift', ['Day', 'Night']);
            $table->text('comments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handover_takeovers');
    }
};