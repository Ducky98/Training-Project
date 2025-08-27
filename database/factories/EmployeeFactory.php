<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
  public function definition(): array
  {
    return [
      'employee_id' => strtoupper(Str::random(6)), // Random Employee ID
      'first_name' => $this->faker->firstName,
      'last_name' => $this->faker->lastName,
      'father_name' => $this->faker->name,
      'mother_name' => $this->faker->name,
      'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
      'status' => $this->faker->boolean ? 1 : 0,
      'mobile_number' => $this->faker->unique()->numerify('98########'),
      'alt_mobile_number' => $this->faker->optional()->numerify('98########'),
      'aadhar_number' => $this->faker->optional()->numerify('############'),
      'pan_number' => $this->faker->optional()->regexify('[A-Z]{5}[0-9]{4}[A-Z]{1}'),
      'kyc_type' => $this->faker->optional()->randomElement(['Aadhar', 'PAN', 'Voter ID']),
      'police_verification_date' => $this->faker->optional()->date(),
      'nok_number' => $this->faker->optional()->phoneNumber,
      'nok_name' => $this->faker->optional()->name,
      'staff_family_id' => $this->faker->optional()->uuid,
      'languages' => json_encode($this->faker->randomElements(['English', 'Hindi', 'Spanish', 'French'], 2)),
      'address' => $this->faker->address,
      'alt_address' => $this->faker->optional()->address,
      'state' => $this->faker->state,
      'country' => $this->faker->country,
      'avatar' => $this->faker->optional()->imageUrl(),

      // Bank Details
      'account_holder_name' => $this->faker->optional()->name,
      'account_number' => $this->faker->optional()->bankAccountNumber,
      'bank_name' => $this->faker->optional()->company,
      'ifsc_code' => $this->faker->optional()->regexify('[A-Z]{4}0[A-Z0-9]{6}'),

      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
