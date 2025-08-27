<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreClientRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return Auth::check();
  }

  /**
   * Get the validation rules.
   */
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'mobile_number' => 'required|string|max:20|unique:clients,mobile_number',
      'email' => 'nullable|email|max:255|unique:clients,email',
      'address' => 'required|string|max:500',
      'relationship_with_patient' => 'required|string|max:255',
      'emergency_contact_name' => 'required|string|max:255',
      'emergency_contact_mobile_number' => 'required|string|max:20',
      'alternate_mobile_number' => 'nullable|string|max:20|unique:clients,alternate_mobile_number',
      'gst_no' => 'nullable|string|max:20|unique:clients,gst_no',
      'id_type' => 'required|string|in:' . implode(',', array_keys(\App\Models\Client::getIdTypes())),
      'id_number' => 'required|string|max:50|unique:clients,id_number,NULL,id,id_type,' . $this->input('id_type'),
      'state' => 'required|string|max:100',
      'country' => 'required|string|max:100',
    ];
  }

  /**
   * Get custom error messages.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'The client name is required.',
      'mobile_number.required' => 'The mobile number is required.',
      'mobile_number.unique' => 'This mobile number is already in use.',
      'email.email' => 'Enter a valid email address.',
      'email.unique' => 'This email address is already registered.',
      'address.required' => 'The address is required.',
      'relationship_with_patient.required' => 'Specify the relationship with the patient.',
      'emergency_contact_name.required' => 'Emergency contact name is required.',
      'emergency_contact_mobile_number.required' => 'Emergency contact mobile number is required.',
      'emergency_contact_mobile_number.max' => 'Emergency contact mobile number should not exceed 20 characters.',
      'alternate_mobile_number.unique' => 'This alternate mobile number is already in use.',
      'gst_no.unique' => 'This GST number is already registered.',
      'id_type.required' => 'Select a valid ID type.',
      'id_type.in' => 'Invalid ID type selected.',
      'id_number.required' => 'The ID number is required.',
      'id_number.unique' => 'This ID number is already in use.',
      'id_number.max' => 'The ID number should not exceed 50 characters.',
      'state.required' => 'The state is required.',
      'state.max' => 'State should not exceed 100 characters.',
      'country.required' => 'The country is required.',
      'country.max' => 'Country should not exceed 100 characters.',
    ];
  }
}
