<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'email',
    'mobile_number',
    'relationship_with_patient',
    'address',
    'id_type', // New field for ID type
    'id_number', // New field for ID number
    'gst_no',
    'state',
    'country',
    'alternate_mobile_number',
    'emergency_contact_name',
    'emergency_contact_mobile_number',
  ];
  protected $dates = ['deleted_at'];
  /**
   * Define a relationship with the Patient model.
   * A client can have multiple patients.
   */
  public function patients()
  {
    return $this->hasMany(Patient::class);
  }
  // Define reusable ID types
  public const ID_TYPES = [
    'aadhar' => 'Aadhar',
    'passport' => 'Passport',
    'driver_license' => "Driver's License",
    'voter_id' => 'Voter ID',
    'other' => 'Other',
  ];

  /**
   * Get ID types for dropdown
   */
  public static function getIdTypes(): array
  {
    return self::ID_TYPES;
  }
}
