<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('water_monitorings', function (Blueprint $table) {
            // Add the new column after 'amount'
            $table->enum('is_hot_water', ['Yes', 'No', 'N/A'])
                  ->nullable()
                  ->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('water_monitorings', function (Blueprint $table) {
            // Define how to reverse the change
            $table->dropColumn('is_hot_water');
        });
    }
};