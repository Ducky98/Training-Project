<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('employees', function (Blueprint $table) {
      // Critical: Index for ->latest() ordering
      $table->index('created_at');

      // Composite index for name searching (most important for your filterColumn)
      $table->index(['first_name', 'last_name'], 'idx_full_name');

      // Individual name indexes for better search performance
      $table->index('first_name');
      $table->index('last_name');

      // Index for employee_id since it's used in routes
      $table->index('employee_id');

      // Composite index for common query patterns
      $table->index(['status', 'created_at'], 'idx_status_created');
    });
  }

  public function down(): void
  {
    Schema::table('employees', function (Blueprint $table) {
      $table->dropIndex(['created_at']);
      $table->dropIndex('idx_full_name');
      $table->dropIndex(['first_name']);
      $table->dropIndex(['last_name']);
      $table->dropIndex(['employee_id']);
      $table->dropIndex('idx_status_created');
    });
  }
};
