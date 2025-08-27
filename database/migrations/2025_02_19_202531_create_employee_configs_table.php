<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('employee_configs', function (Blueprint $table) {
      $table->id();
      $table->string('type')->unique(); // Unique key for each config type
      $table->json('value'); // Stores configuration in JSON format
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('employee_configs');
  }
};
