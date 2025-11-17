<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
    {
        Schema::table('student_statistics', function (Blueprint $table) {
            // Make sure user_id is nullable and unsigned big integer
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Drop old foreign key if it exists
            $table->dropForeign(['user_id']);

            // Add new foreign key rule
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('student_statistics', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            // Revert to NOT NULL if you want (optional)
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // Restore cascade (if that was the original)
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }
    
};
