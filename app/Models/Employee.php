<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
  use HasFactory;

  protected $table = 'employees';

  /**
   * Employee Status Constants
   */
  const STATUS_INACTIVE = 0;
  const STATUS_READY = 1;
  const STATUS_IN_DUTY = 2;
  const STATUS_SUSPENDED = 3;
  const STATUS_LEFT = 4;

  /**
   * Mass assignable fields
   */
  protected $fillable = [
    'employee_id',
    'first_name',
    'last_name',
    'father_name',
    'mother_name',
    'gender',
    'dob',
    'email',
    'category',
    'mobile_number',
    'alt_mobile_number',
    'aadhar_number',
    'pan_number',
    'kyc_type',
    'police_verification_date',
    'nok_name',
    'nok_number',
    'staff_family_type',
    'staff_family_id',
    'languages',
    'address',
    'alt_address',
    'state',
    'country',
    'status',
    'avatar',
    'whatsapp_number',
    'designation',
    'current_salary',
    'current_work_location',
    'account_holder_name',
    'account_number',
    'bank_name',
    'ifsc_code',
  ];

  /**
   * Casts for JSON and date handling
   */
  protected $casts = [
    'languages' => 'array', // Store as JSON but retrieve as an array
    'police_verification_date' => 'date',
    'dob' => 'date',
    'current_salary' => 'decimal:2'
  ];

  /**
   * Accessor: Get full name
   */
  public function getFullNameAttribute()
  {
    return "{$this->first_name} {$this->last_name}";
  }


  /**
   * Accessor: Get readable status
   */
  public function getStatusTextAttribute()
  {
    return match ($this->status) {
      self::STATUS_INACTIVE => 'Inactive',
      self::STATUS_READY => 'Ready to Work',
      self::STATUS_IN_DUTY => 'In Duty (Assigned)',
      self::STATUS_SUSPENDED => 'Suspended',
      self::STATUS_LEFT => 'Left',
      default => 'Unknown',
    };
  }

  /**
   * Scope: Get only active employees (status = 1 or 2)
   */
  public function scopeActive($query)
  {
    return $query->whereIn('status', [self::STATUS_READY, self::STATUS_IN_DUTY]);
  }

  public function documents(): HasMany
  {
    return $this->hasMany(EmployeeDocument::class);
  }
  public function attendances()
  {
    return $this->hasMany(Attendance::class);
  }
  public function salaries()
  {
    return $this->hasMany(Salary::class, 'employee_id', 'employee_id');
  }

  public function totalSalary()
  {
    // Sum the daily_rate for the employee's attendance records
    return $this->attendances()->sum('daily_rate');
  }
}
