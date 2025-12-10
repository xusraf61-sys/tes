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
    Schema::create('days', function (Blueprint $table) {
        $table->id();
        $table->date('date')->unique();
        $table->unsignedBigInteger('study_seconds')->default(0);
        $table->unsignedInteger('examples_solved')->default(0);
        $table->json('sessions')->nullable(); // saqlanishi mumkin
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // auth bilan bog'lash
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('days');
    }
};
