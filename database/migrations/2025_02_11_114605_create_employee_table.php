<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('employees', function (Blueprint $table) {
      $table->id();
      $table->string('employee_id')->unique();
      $table->string('first_name', 100);
      $table->string('last_name', 100)->nullable();
      $table->string('father_name', 100)->nullable();
      $table->string('mother_name', 100)->nullable();
      $table->enum('gender', ['Male', 'Female', 'Other']);
      $table->string('email')->unique()->nullable();
      $table->string('category');
      $table->tinyInteger('status')->default(0)->index();
      $table->string('mobile_number', 10)->index();
      $table->string('alt_mobile_number', 10)->nullable();
      $table->string('aadhar_number', 12)->nullable()->unique();
      $table->string('pan_number', 10)->nullable()->unique();
      $table->string('kyc_type')->nullable();
      $table->date('police_verification_date')->nullable();
      $table->string('nok_number')->nullable();
      $table->string('nok_name')->nullable();
      $table->string('staff_family_type')->nullable();
      $table->string('staff_family_id')->nullable();
      $table->json('languages');
      $table->string('address', 500);
      $table->string('alt_address', 500)->nullable();
      $table->string('state');
      $table->string('country');
      $table->string('avatar')->nullable();
      $table->string('whatsapp_number')->nullable();


      // Bank Details
      $table->string('account_holder_name')->nullable();
      $table->string('account_number', 20)->nullable();
      $table->string('bank_name')->nullable();
      $table->string('ifsc_code', 11)->nullable();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('employees');
  }
};
