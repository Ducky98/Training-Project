<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeConfig extends Model
{
  use HasFactory;

  protected $fillable = ['type', 'value'];

  protected $casts = [
    'value' => 'array', // Auto-cast JSON to array
  ];
}
