<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreInvoiceRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'invoiceId' => 'required|string|unique:invoices,invoiceId',
      'invoiceDate' => 'required|date',
      'billing_details' => 'required|array',
      'billing_details.client_name' => 'required|string',
      'billing_details.client_address' => 'nullable|string',
      'billing_details.client_location' => 'nullable|string',
      'billing_details.client_contact' => 'required|string',
      'billing_details.client_gst_no' => 'nullable|string',
      'date_range' => 'required|string',
      'tax_rate' => 'required|numeric|min:0',
      'discount' => 'required|numeric|min:0',
      'include_gst' => 'nullable|boolean',
      'items' => 'required|array|min:1',
      'items.*.name' => 'required|string',
      'items.*.cg_name' => 'nullable|string',
      'items.*.cg_id' => 'nullable|string',
      'items.*.cost' => 'required|numeric|min:0',
      'items.*.days' => 'required|integer|min:1',
      'items.*.total' => 'required|numeric|min:0',
    ];
  }

  /**
   * Custom validation messages
   */
  public function messages(): array
  {
    return [
      'invoiceId.required' => 'Invoice ID is required.',
      'invoiceId.unique' => 'This Invoice ID is already in use.',
      'invoiceDate.required' => 'Invoice Date is required.',
      'billing_details.required' => 'Billing details are required.',
      'billing_details.client_name.required' => 'Client name is required in billing details.',
      'billing_details.client_contact.required' => 'Client contact is required in billing details.',
      'date_range.required' => 'Invoice period (date range) is required.',
      'tax_rate.required' => 'Tax rate is required.',
      'discount.required' => 'Discount amount is required.',
      'include_gst.boolean' => 'Include GST must be either true or false.',
      'items.required' => 'At least one invoice item is required.',
      'items.min' => 'At least one invoice item must be provided.',

      // Item-specific validation messages
      'items.*.name.required' => 'Item #:position: Name is required.',
      'items.*.cost.required' => 'Item #:position: Cost is required.',
      'items.*.days.required' => 'Item #:position: Number of days is required.',
      'items.*.total.required' => 'Item #:position: Total amount is required.',
    ];
  }

  /**
   * Customize validation errors to include item index and return JSON only for API requests
   */
  protected function failedValidation(Validator $validator)
  {
    // Check if request expects JSON (API or AJAX request)
    if ($this->expectsJson() || $this->ajax()) {
      $errors = $validator->errors()->toArray();

      // Format errors for item-specific validation
      foreach ($errors as $key => $messages) {
        if (strpos($key, 'items.') === 0) {
          preg_match('/items\.(\d+)\.(.*)/', $key, $matches);
          if (!empty($matches)) {
            $index = $matches[1] + 1; // Convert zero-based index to human-friendly
            foreach ($messages as &$message) {
              $message = str_replace(':position:', $index, $message);
            }
            $errors[$key] = $messages;
          }
        }
      }

      throw new ValidationException($validator, response()->json(['errors' => $errors], 422));
    }

    // Default Laravel behavior: Redirect back with errors for web requests
    throw new ValidationException($validator, redirect()->back()->withErrors($validator)->withInput());
  }
}
