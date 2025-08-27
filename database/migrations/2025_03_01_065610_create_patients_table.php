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
    Schema::create('patients', function (Blueprint $table) {
      $table->id();
      $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Links to clients table
      $table->string('first_name');
      $table->string('last_name');
      $table->string('email')->nullable();
      $table->string('phone')->nullable();
      $table->date('date_of_birth')->nullable();
      $table->string('gender')->nullable();
      $table->string('blood_group')->nullable();
      $table->text('allergies')->nullable();
      $table->text('chronic_diseases')->nullable();
      $table->text('medications')->nullable();
      $table->string('doctor_name')->nullable();
      $table->string('doctor_phone')->nullable();
      $table->string('insurance_provider')->nullable();
      $table->string('insurance_policy_number')->nullable();
      $table->string('emergency_contact_name')->nullable();
      $table->string('emergency_contact_phone')->nullable();

      // Home care details
      $table->text('home_address')->nullable();
      $table->string('home_city')->nullable();
      $table->string('home_state')->nullable();
      $table->string('home_zip_code')->nullable();
      $table->string('home_country')->nullable();

      // Status & timestamps
      $table->enum('status', ['active', 'recovered', 'deceased'])->default('active');
      $table->timestamps();
      $table->softDeletes(); // Enables soft delete
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('patients');
  }
};
