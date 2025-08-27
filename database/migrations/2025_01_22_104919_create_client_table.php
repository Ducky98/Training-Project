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
    Schema::create('clients', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email')->nullable()->unique();
      $table->string('mobile_number')->unique();
      $table->string('relationship_with_patient');
      $table->string('id_type');
      $table->string('id_number');
      $table->string('gst_no')->nullable();
      $table->text('address')->nullable();
      $table->string('state')->nullable();
      $table->string('country')->nullable();
      $table->string('alternate_mobile_number')->nullable();
      $table->string('emergency_contact_name');
      $table->string('emergency_contact_mobile_number');
      $table->timestamps();
      $table->softDeletes();
    });
  }


  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('clients');
  }
};
