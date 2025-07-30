<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserIdInFeedbackTable extends Migration
{
    public function up()
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Make sure it's unsigned and matches users.id
            $table->unsignedBigInteger('user_id')->change();

            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            // Revert to previous type if needed (optional)
            // $table->bigInteger('user_id')->change();
        });
    }
}
