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
    Schema::create('salaries', function (Blueprint $table) {
      $table->id();
      $table->string('employee_id');
      $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');

      // Snapshot of employee details
      $table->string('company_name');
      $table->string('employee_name');
      $table->string('designation');
      $table->string('working_location');
      $table->date('joining_date');
      $table->string('bank_name');
      $table->string('account_number');
      $table->string('ifsc_code');

      // Salary period
      $table->string('salary_period');
      $table->integer('total_days');
      $table->integer('paid_days');
      $table->integer('ot_hours')->default(0);

      // Earnings
      $table->decimal('basic_salary', 10, 2)->default(0);
      $table->decimal('hra', 10, 2)->default(0);
      $table->decimal('bonus', 10, 2)->default(0);
      $table->decimal('other_earning', 10, 2)->default(0);
      $table->decimal('arrear', 10, 2)->default(0);
      $table->decimal('total_earnings', 10, 2)->default(0);

      // Deductions
      $table->decimal('provident_fund', 10, 2)->default(0);
      $table->decimal('tax_deduction', 10, 2)->default(0);
      $table->decimal('accommodation', 10, 2)->default(0);
      $table->decimal('other_deduction', 10, 2)->default(0);
      $table->string('other_deduction_remark')->nullable();
      $table->decimal('total_deductions', 10, 2)->default(0);

      // Net amount
      $table->decimal('net_pay', 10, 2)->default(0);

      // Payment details
      $table->enum('mode_of_payment', ['cash', 'bank_transfer', 'cheque', 'upi', 'other']);
      $table->string('transaction_id');
      $table->string('payment_screenshot')->nullable();
      $table->text('note')->nullable();
      $table->date('payment_date');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('salaries');
  }
};
