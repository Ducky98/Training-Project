<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeConfig;
use App\Models\EmployeeDocument;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class EmployeeService
{
  /**
   * Store a new employee
   *
   * @param array $data
   * @return Employee
   * @throws \Exception
   */
  public function store(array $data): Employee
  {
    return DB::transaction(function () use ($data) {
      try {

        // Format police verification date safely
        $policeVerificationDate = Arr::get($data, 'policeVerificationDate')
          ? Carbon::createFromFormat('d-m-Y', $data['policeVerificationDate'])->format('Y-m-d')
          : null;

        // Generate a unique Employee ID
        $employeeId = $this->generateUniqueEmployeeId(Arr::get($data, 'gender'));

        // Create employee record
        $employee = Employee::create([
          'employee_id' => $employeeId,
          'first_name' => Arr::get($data, 'firstName', ''),
          'last_name' => Arr::get($data, 'lastName', null),
          'father_name' => Arr::get($data, 'fatherName', null),
          'mother_name' => Arr::get($data, 'motherName', null),
          'gender' => Arr::get($data, 'gender'),
          'dob' => Arr::get($data, 'dob', null),
          'category' => Arr::get($data,'category'),
          'mobile_number' => Arr::get($data, 'mobileNumber'),
          'whatsapp_number' => Arr::get($data, 'whatsAppNo', null),
          'alt_mobile_number' => Arr::get($data, 'altMobileNumber', null),
          'aadhar_number' => Arr::get($data, 'aadharNumber', null),
          'pan_number' => Arr::get($data, 'panNumber', null),
          'kyc_type' => Arr::get($data, 'kycType', null),
          'police_verification_date' => $policeVerificationDate,
          'nok_name' => Arr::get($data, 'nokName', null),
          'nok_number' => Arr::get($data, 'nokNumber', null),
          'staff_family_type' => Arr::get($data, 'staffFamilyType', null),
          'staff_family_id' => Arr::get($data, 'staffFamilyId', null),
          'languages' => json_encode(Arr::get($data, 'languages', [])),
          'status' => Arr::get($data, 'status', Employee::STATUS_INACTIVE),
          'address' => Arr::get($data, 'address'),
          'state' => Arr::get($data, 'state'),
          'country' => Arr::get($data, 'country'),
        ]);
        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
          $avatar = $data['avatar']; // ✅ Directly access the file
          $avatarPath = $this->storeAvatar($avatar, $employeeId);
          $employee->update(['avatar' => $avatarPath]);
        }

        return $employee;

      } catch (\Exception $e) {
        throw new \Exception("Failed to create employee: " . $e->getMessage());
      }
    });
  }
  public function update(Employee $employee, array $data): Employee
  {

    return DB::transaction(function () use ($employee, $data) {
      try {
        // Update employee record
        $employee->update([
          'first_name' => Arr::get($data, 'firstName', ''),
          'last_name' => Arr::get($data, 'lastName', null),
          'father_name' => Arr::get($data, 'fatherName', null),
          'mother_name' => Arr::get($data, 'motherName', null),
          'gender' => Arr::get($data, 'gender'),
          'dob' => Arr::get($data, 'dob', null),
          'email' => Arr::get($data, 'email'),
          'mobile_number' => Arr::get($data, 'mobileNumber'),
          'whatsapp_number' => Arr::get($data, 'whatsAppNo', null),
          'alt_mobile_number' => Arr::get($data, 'altMobileNumber', null),
          'languages' => json_encode(Arr::get($data, 'languages', [])),
          'status' => Arr::get($data, 'status', Employee::STATUS_INACTIVE),
        ]);

        return $employee; // Return the updated employee instance

      } catch (\Exception $e) {
        throw new \Exception("Failed to update employee: " . $e->getMessage());
      }
    });
  }
  public function updateAddress(Employee $employee, array $data): Employee
  {
    return DB::transaction(function () use ($employee, $data) {
      try {
        $policeVerificationDate = Arr::get($data, 'policeVerificationDate')
          ? Carbon::createFromFormat('d-m-Y', $data['policeVerificationDate'])->format('Y-m-d')
          : null;
        // Update employee record
        $employee->update([
          'aadhar_number' => Arr::get($data, 'aadharNumber', null),
          'pan_number' => Arr::get($data, 'panNumber', null),
          'kyc_type' => Arr::get($data, 'kycType', null),
          'police_verification_date' => $policeVerificationDate,
          'nok_name' => Arr::get($data, 'nokName',null),
          'nok_number' => Arr::get($data, 'nokNumber',null),
          'staff_family_type' => Arr::get($data, 'staffFamilyType',null),
          'staff_family_id' => Arr::get($data, 'staffFamilyId', null),
          'alt_address' => Arr::get($data, 'alt_address', null),
          'address' => Arr::get($data, 'address'),
          'state' => Arr::get($data, 'state'),
          'country' => Arr::get($data, 'country'),
        ]);

        return $employee; // Return the updated employee instance

      } catch (\Exception $e) {
        throw new \Exception("Failed to update employee: " . $e->getMessage());
      }
    });
  }
  /**
   * Generate a unique Employee ID
   *
   * @param string $gender
   * @return string
   */
  private function generateUniqueEmployeeId(string $gender): string
  {
    do {
      $employeeId = $this->generateEmployeeId($gender);
    } while (Employee::where('employee_id', $employeeId)->exists());

    return $employeeId;
  }

  /**
   * Generate Employee ID
   *
   * @param string $gender
   * @return string
   */
  private function generateEmployeeId(string $gender): string
  {
    // Gender-based prefix
    $prefix = match (strtolower($gender)) {
      'male' => 'P',
      'female' => 'K',
      default => 'O', // For Other
    };

    // Get current month and year (MMYY format)
    $datePart = Carbon::now()->format('my'); // Example: "0225" for Feb 2025

    // Generate a random 4-digit number
    $randomNumber = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

    // Combine all parts
    return $prefix . $datePart . $randomNumber;
  }

  /**
   * Store the employee avatar image in employee-specific directory
   *
   * @param UploadedFile|null $avatar
   * @param string $employeeId
   * @return string|null
   * @throws \Exception
   */
  public function storeAvatar(?UploadedFile $avatar, string $employeeId): ?string
  {
    if (!$avatar) {
      return null;
    }

    try {
      // File name with original extension
      $filename = 'avatar.' . $avatar->getClientOriginalExtension();

      // Let's verify the storage path exists
      $storagePath = "employees/{$employeeId}";
      Storage::disk('public')->makeDirectory($storagePath);

      // Store and get full path for debugging
      $path = $avatar->storeAs($storagePath, $filename, 'public');

      if (!$path) {
        throw new \Exception("Failed to store avatar.");
      }

      return $path;
    } catch (\Exception $e) {
      \Log::error('Avatar storage failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      throw $e;
    }
  }

  /**
   * Get Employee Document Configurations.
   *
   * @return array
   */
  public function getEmployeeDocuments(): array
  {
    $config = EmployeeConfig::where('type', 'document')->first();

    if (!$config) {
      return []; // Return empty array if no config exists
    }

    // Ensure the value is properly decoded as an array
    return is_array($config->value) ? $config->value : json_decode($config->value, true);
  }


  /**
   * Update Employee Document Configurations.
   *
   * @param array $documents
   * @return void
   */
  public function updateEmployeeDocuments(array $documents): void
  {
    EmployeeConfig::updateOrCreate(
      ['type' => 'document'],
      ['value' => $documents]
    );
  }

  public function storeDocument(?UploadedFile $document, string $employeeId): ?array
  {
    if (!$document) {
      return null;
    }

    try {
      // File name with original extension
      $filename = 'document_' . time() . '.' . $document->getClientOriginalExtension();

      // Ensure the storage path exists
      $storagePath = "employees/{$employeeId}";
      Storage::disk('public')->makeDirectory($storagePath);

      // Store and get full path
      $path = $document->storeAs($storagePath, $filename, 'public');

      if (!$path) {
        throw new \Exception("Failed to store document.");
      }

      return [
        'filename' => $filename,
        'path' => $path
      ];
    } catch (\Exception $e) {
      \Log::error('Document storage failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      throw $e;
    }
  }
  public function findDocumentPathById(int $id): ?string
  {
    $document = EmployeeDocument::findOrFail($id);

    // File path from database
    $filePath = $document->file_path;

    // Check if file exists in storage
    if (Storage::disk('public')->exists($filePath)) {
      return Storage::disk('public')->path($filePath); // Returns the full local path
    }

    return "File not found.";
  }


  public function deleteDocumentById(int $id): \Illuminate\Http\JsonResponse
  {
    DB::beginTransaction(); // Start transaction

    try {
      $document = EmployeeDocument::findOrFail($id);
      $filePath = $document->file_path;

      // Check if the file exists
      if (Storage::disk('public')->exists($filePath)) {
        if (!Storage::disk('public')->delete($filePath)) {
          throw new \Exception("Failed to delete file from storage.");
        }
      } else {
        throw new \Exception("File not found in storage.");
      }

      // Delete record from DB
      if (!$document->delete()) {
        throw new \Exception("Failed to delete document record.");
      }

      DB::commit(); // Commit transaction if both succeed
      return response()->json(['message' => 'Document deleted successfully'], 200);

    } catch (\Exception $e) {
      DB::rollBack(); // Rollback transaction on failure
      \Log::error('Document deletion failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return response()->json(['error' => 'Document deletion failed: ' . $e->getMessage()], 500);
    }
  }


  public function deleteDocumentsByEmployeeId(string $employeeCode): bool
  {
    DB::beginTransaction();

    try {
      $folderPath = "employees/{$employeeCode}";

      // Delete folder if it exists
      if (Storage::disk('public')->exists($folderPath)) {
        if (!Storage::disk('public')->deleteDirectory($folderPath)) {
          \Log::error('Failed to delete directory', ['folder' => $folderPath]);
          // But do not stop the process
        }
      } else {
        \Log::info('No directory found to delete', ['folder' => $folderPath]);
      }

      // Delete document records if they exist
      $documents = EmployeeDocument::where('employee_id', $employeeCode)->get();

      if ($documents->isNotEmpty()) {
        EmployeeDocument::where('employee_id', $employeeCode)->delete();
      } else {
        \Log::info('No document records found to delete', ['employee_code' => $employeeCode]);
      }

      DB::commit();
      return true;

    } catch (\Exception $e) {
      DB::rollBack();
      \Log::error('Failed to delete documents for employee', [
        'employee_code' => $employeeCode,
        'error' => $e->getMessage()
      ]);
      // Still return true because we don't want to block employee deletion
      return false;
    }
  }



  /**
   * Find an employee by ID or fail.
   *
   * @param string $employee_id
   * @return \App\Models\Employee
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
   */
  public function findEmployeeByIdOrFail(string $employee_id): Employee
  {
    return Employee::where('employee_id', $employee_id)->firstOrFail();
  }

  /**
   * Update employee information if needed.
   *
   * @param \App\Models\Employee $employee
   * @param array $data
   * @return \App\Models\Employee
   */
  public function updateEmployeeInfo(Employee $employee, array $data): Employee
  {
    $updateData = [];

    // Always update if provided and value is different
    if (!empty($data['designation']) && $employee->designation !== $data['designation']) {
      $updateData['designation'] = $data['designation'];
    }

    if (!empty($data['current_work_location']) && $employee->current_work_location !== $data['current_work_location']) {
      $updateData['current_work_location'] = $data['current_work_location'];
    }

    // Update only if not already filled and value is different
    $conditionalFields = ['bank_name', 'account_number', 'ifsc_code'];
    foreach ($conditionalFields as $field) {
      if (!empty($data[$field]) && empty($employee->$field) && $employee->$field !== $data[$field]) {
        $updateData[$field] = $data[$field];
      }
    }

    if (!empty($updateData)) {
      $employee->update($updateData);
    }

    return $employee;
  }



  /**
   * Store payment screenshot.
   *
   * @param \Illuminate\Http\UploadedFile $file
   * @param string $employee_id
   * @return string
   */
  public function storePaymentScreenshot(UploadedFile $file, string $employee_id): string
  {
    // Define the storage path with the desired structure
    $storagePath = "employee/{$employee_id}";

    // Create a unique filename using employee_id and timestamp
    $filename = 'payment_' . $employee_id . '_' . time() . '.' . $file->getClientOriginalExtension();

    // Store the file in the 'employee/{employee_id}' folder in the public disk
    $path = $file->storeAs($storagePath, $filename, 'public');

    return $path;
  }

  /**
   * Calculate the number of days between two dates.
   *
   * @param string $monthFrom
   * @param string $yearFrom
   * @param string $monthTo
   * @param string $yearTo
   * @return int
   */
  public function calculateTotalDays(string $monthFrom, string $yearFrom, string $monthTo, string $yearTo): int
  {
    $fromDate = new \DateTime("$yearFrom-$monthFrom-01");
    $toDate = new \DateTime("$yearTo-$monthTo-01");
    $toDate->modify('last day of this month');

    $interval = $fromDate->diff($toDate);
    return $interval->days + 1;
  }

  /**
   * Convert a number to words representation (Indian currency format)
   *
   * @param float $number The number to convert
   * @return string The number in words
   */
  public function convertNumberToWords($number)
  {
    if ($number == 0) {
      return 'Zero Rupees Only';
    }

    // Separate the whole number and decimal parts
    $number_parts = explode('.', number_format($number, 2, '.', ''));
    $whole_number = $number_parts[0];
    $decimal = isset($number_parts[1]) ? $number_parts[1] : '00';

    // Convert whole number to words
    $words = $this->convertWholeNumberToWords($whole_number);

    // Add rupees
    $result = $words ? $words . ' Rupees' : '';

    // Add paise if applicable
    if ($decimal > 0) {
      $decimal_words = $this->convertWholeNumberToWords($decimal);
      $result .= ' and ' . $decimal_words . ' Paise';
    }

    return $result . ' Only';
  }

  /**
   * Helper method to convert whole numbers to words
   *
   * @param string $number The whole number as a string
   * @return string The whole number in words
   */
  protected function convertWholeNumberToWords($number)
  {
    $number = ltrim($number, '0');
    if (empty($number)) {
      return '';
    }

    $words = [];
    $units = [
      '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
      'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    ];

    $tens = [
      '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
    ];

    // For Indian numbering system (lakhs, crores)
    $len = strlen($number);

    // Process billions (Arab in Indian system)
    if ($len > 9) {
      $billions = intval(substr($number, 0, $len - 9));
      $words[] = $this->convertWholeNumberToWords($billions) . ' Billion';
      $number = substr($number, $len - 9);
      $len = strlen($number);
    }

    // Process crores (100 lakhs = 10 million)
    if ($len > 7) {
      $crores = intval(substr($number, 0, $len - 7));
      $words[] = $this->convertWholeNumberToWords($crores) . ' Crore';
      $number = substr($number, $len - 7);
      $len = strlen($number);
    }

    // Process lakhs (100 thousand)
    if ($len > 5) {
      $lakhs = intval(substr($number, 0, $len - 5));
      $words[] = $this->convertWholeNumberToWords($lakhs) . ' Lakh';
      $number = substr($number, $len - 5);
      $len = strlen($number);
    }

    // Process thousands
    if ($len > 3) {
      $thousands = intval(substr($number, 0, $len - 3));
      $words[] = $this->convertWholeNumberToWords($thousands) . ' Thousand';
      $number = substr($number, $len - 3);
      $len = strlen($number);
    }

    // Process hundreds
    if ($len > 2) {
      $hundreds = intval(substr($number, 0, $len - 2));
      $words[] = $units[$hundreds] . ' Hundred';
      $number = substr($number, $len - 2);
      $len = strlen($number);
    }

    // Process tens and units
    if ($len > 0) {
      $value = intval($number);
      if ($value < 20) {
        $words[] = $units[$value];
      } else {
        $unit_digit = $value % 10;
        $ten_digit = floor($value / 10);

        $words[] = $tens[$ten_digit] . ($unit_digit > 0 ? ' ' . $units[$unit_digit] : '');
      }
    }

    return implode(' ', $words);
  }

  public function createSalary($employee_id, $validated, Request $request)
  {
    // Calculate the totals
    $totalEarnings = $validated['basic_salary'] + $validated['hra'] + $validated['bonus'] +
      $validated['other_earning'] + $validated['arrear'];

    $totalDeductions = $validated['provident_fund'] + $validated['tax_deduction'] +
      $validated['accommodation'] + $validated['other_deduction'];

    $netPay = $totalEarnings - $totalDeductions;

    // Handle file upload if provided
    $paymentScreenshotPath = null;
    if ($request->hasFile('payment_screenshot')) {
      $paymentScreenshotPath = $this->storePaymentScreenshot(
        $request->file('payment_screenshot'),
        $employee_id
      );
    }

    // Create salary record
    Salary::create([
      'employee_id' => $employee_id,

      // Snapshot of employee details
      'company_name' => $validated['company_name'],
      'employee_name' => $validated['name'],
      'designation' => $validated['designation'],
      'working_location' => $validated['current_work_location'],
      'joining_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['joining_date'])->format('Y-m-d'),
      'account_number' => $validated['account_number'],
      'bank_name' => $validated['bank_name'],
      'ifsc_code' => $validated['ifsc_code'],

      // Salary period
      'salary_period' => $validated['salary_month_from'] . '-' . $validated['salary_year_from'] . ' to ' .
        $validated['salary_month_to'] . '-' . $validated['salary_year_to'],
      'total_days' => $validated['total_days'],
      'paid_days' => $validated['paid_days'],
      'ot_hours' => $validated['ot_hours'],

      // Earnings
      'basic_salary' => $validated['basic_salary'],
      'hra' => $validated['hra'],
      'bonus' => $validated['bonus'],
      'other_earning' => $validated['other_earning'],
      'arrear' => $validated['arrear'],
      'total_earnings' => $totalEarnings,

      // Deductions
      'provident_fund' => $validated['provident_fund'],
      'tax_deduction' => $validated['tax_deduction'],
      'accommodation' => $validated['accommodation'],
      'other_deduction' => $validated['other_deduction'],
      'other_deduction_remark' => $validated['other_deduction_remark'],
      'total_deductions' => $totalDeductions,

      // Net pay
      'net_pay' => $netPay,

      // Payment details
      'mode_of_payment' => $validated['mode_of_payment'],
      'transaction_id' => $validated['transaction_id'],
      'payment_screenshot' => $paymentScreenshotPath,
      'note' => $validated['note'],
      'payment_date' => $validated['payment_date']
    ]);
  }





}
