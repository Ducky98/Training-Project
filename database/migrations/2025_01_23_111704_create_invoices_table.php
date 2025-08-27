<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('invoices', function (Blueprint $table) {
      $table->id();
      $table->string('invoiceId')->unique();
      $table->date('invoiceDate');
      $table->json('company_details'); // Store company details in JSON
      $table->json('billing_details'); // Store client details in JSON
      $table->string('date_range');
      $table->decimal('tax_rate', 8, 2)->default(0);
      $table->decimal('discount', 8, 2)->default(0);
      $table->boolean('include_gst')->default(false);
      $table->timestamps();
    });

    Schema::create('invoice_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');

      $table->string('name'); // Store client/patient name as raw text
      $table->string('supervisor')->nullable();
      $table->string('shift')->nullable();
      $table->string('code')->nullable();
      $table->string('cg_name')->nullable(); // Store CG (caregiver) name as raw text
      $table->string('cg_id')->nullable()->index(); // Store CG (caregiver) ID as raw text
      $table->decimal('cost', 10, 2);
      $table->integer('days');
      $table->decimal('total', 10, 2);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('invoice_items');
    Schema::dropIfExists('invoices');
  }
};
