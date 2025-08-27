<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'employee_id',
    'company_name',
    'employee_name',
    'designation',
    'working_location',
    'joining_date',
    'bank_name',
    'account_number',
    'ifsc_code',
    'salary_period',
    'total_days',
    'paid_days',
    'ot_hours',
    'basic_salary',
    'hra',
    'bonus',
    'other_earning',
    'arrear',
    'total_earnings',
    'provident_fund',
    'tax_deduction',
    'accommodation',
    'other_deduction',
    'other_deduction_remark',
    'total_deductions',
    'net_pay',
    'mode_of_payment',
    'transaction_id',
    'payment_screenshot',
    'note',
    'payment_date'
  ];
  protected $casts = [
    'payment_date' => 'date',
  ];

  /**
   * Get the employee that owns the salary.
   */
  public function employee()
  {
    // Linking salary with employee using employee_id as foreign key and 'employee_id' in employees table as local key
    return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
  }
}
