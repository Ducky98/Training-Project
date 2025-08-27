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
        Schema::table('employees', function (Blueprint $table) {
          $table->string('designation')->nullable()->after('whatsapp_number');
          $table->string('current_work_location')->nullable()->after('designation');
          $table->decimal('current_salary', 10, 2)->nullable()->after('current_work_location');  // New column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
          $table->dropColumn(['designation', 'current_work_location','current_salary']);
        });
    }
};
