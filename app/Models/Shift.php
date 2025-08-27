<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
  protected $fillable = [
    'name',
    'hours',
    'color',
  ];

  public function attendances()
  {
    return $this->hasMany(Attendance::class);
  }
}
