<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
  protected $fillable = [
    'employee_id',
    'shift_id',
    'date',
    'notes',
    'status',
    'daily_rate'
  ];

  protected $casts = [
    'date' => 'date',
  ];

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  public function shift()
  {
    return $this->belongsTo(Shift::class);
  }
}
