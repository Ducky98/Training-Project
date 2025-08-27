<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminConfigurations extends Model
{
  // Define which fields are mass assignable
  protected $fillable = ['key', 'value', 'description'];
}
