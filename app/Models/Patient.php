<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'client_id',
    'first_name',
    'last_name',
    'email',
    'phone',
    'date_of_birth',
    'gender',
    'blood_group',
    'allergies',
    'chronic_diseases',
    'medications',
    'doctor_name',
    'doctor_phone',
    'insurance_provider',
    'insurance_policy_number',
    'emergency_contact_name',
    'emergency_contact_phone',
    'home_address',
    'home_city',
    'home_state',
    'home_zip_code',
    'home_country',
    'status',
  ];

  /**
   * Relationship: A patient belongs to a client (family member).
   */
  public function client()
  {
    return $this->belongsTo(Client::class);
  }

  /**
   * Relationship: A patient can have multiple nurse assignments.
   */

}
