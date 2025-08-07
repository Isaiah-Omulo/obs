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
    Schema::create('escalations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('occurrence_id');
        $table->string('recipient_email');
        $table->string('subject');
        $table->text('message');
        $table->timestamps();

        $table->foreign('occurrence_id')->references('id')->on('occurrences')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalations');
    }
};
