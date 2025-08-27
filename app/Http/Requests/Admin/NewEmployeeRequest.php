<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewEmployeeRequest extends FormRequest
{
  public function authorize()
  {
    return true; // Change this based on authorization logic if needed
  }

  public function rules()
  {
    return [
      'avatar' => 'nullable|image|max:800', // Allows images up to 2MB
      'firstName' => 'required|string|max:100',
      'lastName' => 'nullable|string|max:100',
      'fatherName' => 'nullable|string|max:100',
      'motherName' => 'nullable|string|max:100',
      'gender' => 'required|in:Male,Female,Other',
      'category' => 'required|string|max:100',
      'mobileNumber' => 'required|digits:10',
      'whatsAppNo' => 'nullable|digits:10',
      'altMobileNumber' => 'nullable|digits:10',
      'aadharNumber' => 'nullable|regex:/^[2-9]{1}[0-9]{11}$/',
      'panNumber' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
      'kycType' => 'nullable|string|max:255',
      'policeVerificationDate' => 'nullable|date|before_or_equal:today',
      'nokName' => 'nullable|string|max:255',
      'nokNumber' => 'nullable|string|max:255',
      'staffFamilyType' => 'nullable|string|max:255',
      'staffFamilyId' => 'nullable|string|max:255',
      'languages' => 'required|array',
      'languages.*' => 'string',
      'status' => 'nullable|integer',
      'address' => 'required|string|max:500',
      'state' => 'required|string|max:255',
      'country' => 'required|string|max:255',
    ];
  }

  public function messages()
  {
    return [
      'firstName.required' => 'First name is required.',
      'lastName.required' => 'Last name is required.',
      'gender.required' => 'Please select a gender.',
      'mobileNumber.required' => 'Mobile number is required.',
      'mobileNumber.digits' => 'Mobile number must be 10 digits.',
      'whatsAppNo.digits' => 'WhatsApp number number must be 10 digits.',
      'aadharNumber.regex' => 'Invalid Aadhar number format.',
      'panNumber.regex' => 'Invalid PAN number format.',
      'country.required' => 'Country is required.',
    ];
  }
}
