<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewEmployeeRequest;
use App\Models\AdminConfigurations;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\Salary;
use App\Services\AdminConfigService;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Vinkla\Hashids\Facades\Hashids;

/**
 * Class EmployeeController
 * Handles employee management operations such as listing, creating, updating, and deleting employees.
 */
class EmployeeController extends Controller
{
  protected EmployeeService $employeeService;
  protected $configService;

  /**
   * Inject EmployeeService into the Controller.
   *
   * @param EmployeeService $employeeService
   */
  public function __construct(EmployeeService $employeeService, AdminConfigService $configService)
  {
    $this->configService = $configService;
    $this->employeeService = $employeeService;
  }

  /**
   * Display a listing of employees.
   * Supports both web and AJAX (for Datatables).
   *
   * @return View|JsonResponse
   */
  public function index(): View|JsonResponse
  {
    if (request()->ajax()) {
      try {
        // Optimized base query with early filtering
        $employees = Employee::select([
          'id', 'employee_id', 'first_name', 'last_name',
          'status', 'mobile_number', 'created_at'
        ])
          ->latest('created_at') // Explicit column for index usage
          ->when(request()->filled('search.value'), function($query) {
            $search = request()->input('search.value');
            $query->where(function($q) use ($search) {
              $q->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('employee_id', 'LIKE', "%{$search}%")
                ->orWhere('mobile_number', 'LIKE', "%{$search}%");
            });
          });

        return datatables()->of($employees)
          ->addIndexColumn()
          ->addColumn('full_name', function($row) {
            return trim($row->first_name . ' ' . ($row->last_name ?? ''));
          })
          ->filterColumn('full_name', function ($query, $keyword) {
            // Optimized search using indexes
            $query->where(function($q) use ($keyword) {
              $q->where('first_name', 'LIKE', "%{$keyword}%")
                ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                ->orWhereRaw("CONCAT(first_name, ' ', COALESCE(last_name, '')) LIKE ?", ["%{$keyword}%"]);
            });
          })
          ->orderColumn('full_name', function($query, $order) {
            $query->orderBy('first_name', $order)->orderBy('last_name', $order);
          })
          ->orderColumn('DT_RowIndex', function($query, $order) {
            $query->orderBy('id', $order);
          })
          ->addColumn('status', function ($row) {
            $badgeClass = match ($row->status) {
              Employee::STATUS_INACTIVE => 'text-secondary bg-light',
              Employee::STATUS_READY => 'text-success bg-light-success',
              Employee::STATUS_IN_DUTY => 'text-primary bg-light-primary',
              Employee::STATUS_SUSPENDED => 'text-warning bg-light-warning',
              Employee::STATUS_LEFT => 'text-danger bg-light-danger',
              default => 'text-dark bg-light',
            };
            return '<span class="badge ' . $badgeClass . '">' . $row->status_text . '</span>';
          })
          ->addColumn('mobile_number', function($row) {
            return $row->mobile_number ?: 'N/A';
          })
          ->addColumn('action', function($row) {
            return '<a href="' . route('admin.employee.show', $row->employee_id) . '" class="btn btn-sm btn-primary">View</a>';
          })
          ->editColumn('created_at', function($row) {
            return $row->created_at->format('d M Y');
          })
          ->rawColumns(['action', 'status'])
          ->toJson();

      } catch (\Exception $e) {
        Log::error('DataTables error: ' . $e->getMessage(), [
          'trace' => $e->getTraceAsString(),
          'request' => request()->all()
        ]);

        return response()->json([
          'error' => true,
          'message' => config('app.debug') ? $e->getMessage() : 'Server error occurred',
          'draw' => (int) request()->get('draw', 0),
          'recordsTotal' => 0,
          'recordsFiltered' => 0,
          'data' => []
        ], 500);
      }
    }

    return view('admin.employee.index');
  }





  /**
   * Show the form for creating a new employee.
   *
   * @return View
   */
  public function create(): View
  {
    return view('admin.employee.create');
  }

  /**
   * Store a newly created employee.
   *
   * @param NewEmployeeRequest $request
   * @return RedirectResponse
   */
  public function store(NewEmployeeRequest $request): RedirectResponse
  {
    try {
      $this->employeeService->store($request->validated());
      return redirect()->route('admin.employee.index')->with('success', 'Employee added successfully.');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Failed to create employee: ' . $e->getMessage());
    }
  }









  /**
   * Display the specified employee.
   *
   * @param string $employee_id
   * @return View
   */
  public function show($employee_id): View
  {
    $employee = Employee::where('employee_id', $employee_id)
      ->select(['id','employee_id','first_name','last_name','father_name','mother_name','gender','dob','email', 'category','mobile_number','alt_mobile_number','aadhar_number',
        'pan_number','kyc_type','police_verification_date','nok_name','bank_name','account_number','ifsc_code','nok_number','staff_family_type','staff_family_id','languages','address','alt_address',
        'state','country','whatsapp_number','avatar'
      ])
      ->firstOrFail();

    return view('admin.employee.show', ['employee' => $employee]);
  }











  /**
   * Show the form for editing the specified employee.
   *
   * @param string $employee_id
   * @return View
   */
  public function edit(string $employee_id): View
  {
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();
    return view('admin.employee.edit', ['employee' => $employee]);
  }

  /**
   * Show the form for editing the specified employee.
   *
   * @param string $employee_id
   * @return View
   */
  public function editBankDetail(string $employee_id): View
  {
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();
    return view('admin.employee.edit_bank', ['employee' => $employee]);
  }










  /**
   * Update the specified employee.
   *
   * @param Request $request
   * @param string $employee_id
   * @return RedirectResponse
   */
  public function update(Request $request, $employee_id): RedirectResponse
  {
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

      // Validate request data
      $validatedData = $request->validate([
        'firstName' => 'required|string|max:255',
        'lastName' => 'nullable|string|max:255',
        'category' => 'nullable|string|max:255',
        'dob' => 'nullable|date|before:today',
        'fatherName' => 'nullable|string|max:255',
        'motherName' => 'nullable|string|max:255',
        'gender' => 'required|in:Male,Female,Other',
        'status' => 'required|in:' . implode(',', [
            Employee::STATUS_INACTIVE,
            Employee::STATUS_READY,
            Employee::STATUS_IN_DUTY,
            Employee::STATUS_SUSPENDED,
            Employee::STATUS_LEFT,
          ]),
        'languages' => 'required|array',
        'languages.*' => 'string',
        'mobileNumber' => 'required|digits:10|unique:employees,mobile_number,' . $employee->id,
        'whatsAppNo' => 'nullable|digits:10',
        'altMobileNumber' => 'nullable|digits:10',
        'email' => 'nullable|email|unique:employees,email,' . $employee->id,
      ]);

      try {
        // Pass validated data to the service
        $this->employeeService->update($employee, $validatedData);

        return redirect()->route('admin.employee.show', $employee_id)
          ->with('success', 'Employee updated successfully.');
      } catch (\Exception $e) {
        return redirect()->back()
          ->with('error', 'Failed to update employee: ' . $e->getMessage());
      }
  }

  /**
   * Update the specified employee.
   *
   * @param Request $request
   * @param string $employee_id
   * @return RedirectResponse
   */
  public function updateBank(Request $request, $employee_id): RedirectResponse
  {
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

    // Validate request data
    $validatedData = $request->validate([
      'account_holder_name' => 'nullable|string|max:255',
      'account_number' => 'nullable|string|max:20',
      'bank_name' => 'nullable|string|max:255',
      'ifsc_code' => 'nullable|string|size:11',
      'designation' => 'nullable|string|max:255'
    ]);

    try {
      // Corrected update call on the instance
      $employee->update($validatedData);

      return redirect()->route('admin.employee.showSecurity', $employee_id)
        ->with('success', 'Employee updated successfully.');
    } catch (\Exception $e) {
      return redirect()->back()
        ->with('error', 'Failed to update employee: ' . $e->getMessage());
    }
  }


  public function delete($employee_id): RedirectResponse
  {
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

    DB::beginTransaction();

    try {
      // Attempt to delete documents, but failure here should not block
      $this->employeeService->deleteDocumentsByEmployeeId($employee->employee_id);

      // Delete the employee regardless
      $employee->delete();

      DB::commit();

      return redirect()->route('admin.employee.index')
        ->with('success', 'Employee deleted successfully!');
    } catch (\Exception $e) {
      DB::rollBack();

      \Log::error('Employee deletion failed', [
        'employee_id' => $employee->id,
        'employee_code' => $employee->employee_id,
        'error' => $e->getMessage()
      ]);

      return redirect()->back()->with('error', 'Failed to delete employee.');
    }
  }










  /**
   * Show the form for editing address of the specified employee.
   *
   * @param string $employee_id
   * @return View
   */
  public function editAddress($employee_id): View
  {
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();
    return view('admin.employee.edit_address', ['employee' => $employee]);
  }







  /**
   * Update the specified employee's address and NOK details.
   *
   * @param Request $request
   * @param string $employee_id
   * @return RedirectResponse
   */
  public function updateAddress(Request $request, $employee_id): RedirectResponse
  {
    // Retrieve the full employee object instead of just ID
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

    // Validate request data
    $validatedData = $request->validate([
      'aadharNumber' => 'nullable|string|max:12|regex:/^\d{12}$/',
      'panNumber' => 'nullable|string|max:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
      'kycType' => 'nullable|string|max:255',
      'policeVerificationDate' => 'nullable|date',
      'nokName' => 'nullable|string|max:255',
      'nokNumber' => 'nullable|string|max:255',
      'staffFamilyType' => 'nullable|string|max:255',
      'staffFamilyId' => 'nullable|string|max:255',
      'alt_address' => 'nullable|string|max:500',
      'address' => 'required|string|max:500',
      'state' => 'required|string|max:255',
      'country' => 'required|string|max:255',
    ]);

    try {
      // Pass validated data to the service
      $this->employeeService->updateAddress($employee, $validatedData);

      return redirect()->route('admin.employee.show', $employee_id)
        ->with('success', 'Employee updated successfully.');
    } catch (\Exception $e) {
      return redirect()->back()
        ->with('error', 'Failed to update employee: ' . $e->getMessage());
    }
  }








  /**
   * Show the form for editing the employee's profile photo.
   *
   * @param string $employee_id
   * @return View
   */
  public function editProfilePhoto(string $employee_id): View
  {
    $employee = Employee::where('employee_id', $employee_id)->select('id', 'employee_id', 'avatar')->firstOrFail();
    return view('admin.employee.edit_profile_photo', ['employee' => $employee]);
  }








  /**
   *
   * Update the employee's profile photo.
   *
   * @param Request $request
   * @param Employee $id
   * @return RedirectResponse
   */
  public function updateProfilePhoto(Request $request, Employee $id): RedirectResponse
  {
    $request->validate([
      'avatar' => 'required|image|mimes:jpeg,png,jpg|max:800',
    ]);

    try {
      // Delete old photo if exists
      if ($id->avatar && Storage::disk('public')->exists($id->avatar)) {
        Storage::disk('public')->delete($id->avatar);
      }

      // Store new avatar using the service
      $path = $this->employeeService->storeAvatar($request->file('avatar'), $id->employee_id);

      // Update employee record
      $id->avatar = $path;
      $id->save();

      return redirect()->route('admin.employee.show', $id->employee_id)->with('success', 'Profile photo updated successfully.');
    } catch (\Exception $e) {
      \Log::error('Profile photo update failed', [
        'employee_id' => $id->id,
        'error' => $e->getMessage()
      ]);

      return redirect()->back()->with('error', 'Failed to update profile photo');
    }
  }








  /**
   * Display the specified employee's Security Details.
   *
   * @param string $employee_id
   * @return View
   */
  public function showSecurity(string $employee_id): View
  {
    $employee = Employee::where('employee_id', $employee_id)
      ->with('documents') // Load documents relation
      ->select(['id', 'employee_id', 'first_name', 'last_name', 'email', 'mobile_number', 'avatar', 'bank_name', 'account_number','ifsc_code', 'designation'])
      ->firstOrFail();
    return view('admin.employee.show_security', compact('employee'));
  }
  /**
   * Display the specified employee's Security Details.
   *
   * @param string $employee_id
   * @return View
   */
  // Controller method - fixed to pass salary data to view
  public function showSalary(string $employee_id)
  {
    $employee = Employee::where('employee_id', $employee_id)
      ->select(['id', 'employee_id', 'first_name', 'last_name', 'email', 'mobile_number', 'avatar'])
      ->firstOrFail();

    // Total salary already paid
    $paidSalary = Salary::where('employee_id', $employee_id)->sum('net_pay');

    // Total salary that should be given based on attendance
    $totalDueSalary = Attendance::where('employee_id', $employee_id)
      ->whereNotNull('daily_rate')
      ->sum('daily_rate');

    // Count of attendance records
    $attendanceCount = Attendance::where('employee_id', $employee_id)->count();

    // Last attendance record
    $lastAttendance = Attendance::where('employee_id', $employee_id)
      ->latest('date')
      ->first();

    // Last paid salary record
    $lastSalary = Salary::where('employee_id', $employee_id)
      ->latest('payment_date')
      ->first();
    $salaries = Salary::where('employee_id', $employee_id)
      ->orderByDesc('payment_date')
      ->get();
    return view('admin.employee.show_salary_slip', compact(
      'employee',
      'salaries',
      'paidSalary',
      'totalDueSalary',
      'attendanceCount',
      'lastAttendance',
      'lastSalary'
    ));
  }




  public function destroySalary(int $salary_id)
  {
    try {
      $salary = Salary::findOrFail($salary_id);
      $salary->delete();

      return response()->json(['success' => true, 'message' => 'Salary record deleted successfully']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error occurred while deleting the record']);
    }
  }

  public function createSalary($employee_id)
  {
    $company_name = $this->configService->getAllInvoiceConfigs();
    $employee = Employee::where('employee_id', $employee_id)
      ->select(['id', 'employee_id', 'first_name', 'last_name', 'designation', 'current_work_location', 'bank_name','account_number','ifsc_code','created_at'])
      ->firstOrFail();
//    $previousData = Salary::where('employee')
    return view('admin.employee.create_salary', compact('employee', 'company_name'));
  }
  /**
   * Store a newly created salary record in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  string  $employee_id
   * @return RedirectResponse
   */
  public function storeSalary(Request $request, $employee_id)
  {
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

    // Validate the request data
    $validated = $request->validate([
      'company_name' => 'required|string|max:255',
      'salary_month_from' => 'required|string',
      'salary_year_from' => 'required|string',
      'salary_month_to' => 'required|string',
      'salary_year_to' => 'required|string',
      'name' => 'required|string',
      'designation' => 'required|string|max:255',
      'current_work_location' => 'required|string|max:255',
      'joining_date' => 'required|date_format:d-m-Y',
      'bank_name' => 'required|string|max:255',
      'account_number' => 'required|string|max:255',
      'ifsc_code' => 'required|string|max:11|regex:/[A-Za-z]{4}0[A-Za-z0-9]{6}/',
      'total_days' => 'required|integer|min:1',
      'paid_days' => 'required|integer|min:1',
      'ot_hours' => 'required|integer|min:0',
      'basic_salary' => 'required|numeric|min:0',
      'hra' => 'required|numeric|min:0',
      'bonus' => 'required|numeric|min:0',
      'other_earning' => 'required|numeric|min:0',
      'arrear' => 'required|numeric|min:0',
      'provident_fund' => 'required|numeric|min:0',
      'tax_deduction' => 'required|numeric|min:0',
      'accommodation' => 'required|numeric|min:0',
      'other_deduction' => 'required|numeric|min:0',
      'other_deduction_remark' => 'nullable|string|max:255',
      'mode_of_payment' => 'required|string|in:cash,bank_transfer,cheque,upi,other',
      'transaction_id' => 'required|string|max:255',
      'payment_screenshot' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'note' => 'nullable|string',
      'payment_date' => 'required|date'
    ]);

    // Update employee information if needed
    $this->employeeService->updateEmployeeInfo($employee, $validated);

    // Call createSalary to insert the salary record
    $this->employeeService->createSalary($employee_id, $validated, $request);

    return redirect()->route('admin.employee.showSalary', $employee_id)
      ->with('success', 'Salary record added successfully.');
  }


  /**
   * Generate and return a salary slip view for a specific salary record
   *
   * @param Salary $salary The salary model object containing payment details
   * @return \Illuminate\View\View
   */
  public function printSalarySlip(Salary $salary_id)
  {
    $amount_in_words = $this->employeeService->convertNumberToWords($salary_id->net_pay);
    // Pass the variable names as strings to compact
    return view('admin.employee.print_salary', compact('salary_id', 'amount_in_words'));
  }





  /**
   * Display the specified employee's documents.
   *
   * @param string $employee_id
   * @return View
   */
  public function showDocuments(string $employee_id): View
  {
    $employee = Employee::where('employee_id', $employee_id)
      ->with('documents') // Load documents relation
      ->select(['id', 'employee_id', 'first_name', 'last_name', 'email', 'mobile_number', 'avatar'])
      ->firstOrFail();

    // Get required documents from EmployeeConfig
    $requiredDocuments = $this->employeeService->getEmployeeDocuments();

    // Get uploaded documents as an associative array
    $uploadedDocuments = $employee->documents->keyBy('document_type')->toArray();

    return view('admin.employee.show_document', compact('employee', 'requiredDocuments', 'uploadedDocuments'));
  }






  public function uploadDocument(string $employee_id, string $document): View
  {
    $employee = Employee::where('employee_id', $employee_id)->with('documents')->firstOrFail();
    return view('admin.employee.upload_document', compact('employee', 'document'));
  }








  public function editDocument(string $employee_id, string $document): View
  {
    $employee = Employee::where('employee_id', $employee_id)->with('documents')->firstOrFail();
    return view('admin.employee.upload_document', compact('employee', 'document'));
  }








  public function storeUploadDocument(Request $request, $id): RedirectResponse
  {
    $employee = Employee::with('documents')->select(['id', 'employee_id'])->findOrFail($id);
    $request->validate([
      'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB limit
    ]);
    try {
      // Store new document
      $documentData = $this->employeeService->storeDocument($request->file('document_file'), $employee->employee_id);

      // Save document details in EmployeeDocument model
      $employee->documents()->create([
        'document_name' => $documentData['filename'],
        'document_type' => $request->document_type,
        'file_path' => $documentData['path'],
      ]);

      return redirect()->route('admin.employee.showDocuments', $employee->employee_id)
        ->with('success', 'Document uploaded successfully.');
    } catch (\Exception $e) {
      \Log::error('Document upload failed', [
        'employee_id' => $employee->id,
        'error' => $e->getMessage()
      ]);

      return redirect()->back()->with('error', 'Failed to upload document.');
    }
  }




  public function deleteDocument($id): RedirectResponse
  {
    Log::info('Trigger event fired');

    try {

      $this->employeeService->deleteDocumentById($id);

      return redirect()->back()
        ->with('success', 'Document deleted successfully.');
    } catch (\Exception $e) {
      \Log::error('Document deletion failed', [
        'document_id' => $id,
        'error' => $e->getMessage()
      ]);

      return redirect()->back()->with('error', 'Failed to delete document.');
    }
  }

  public function printIdCard($hashed_id, Request $request)
  {
    // Decode the hashed ID
    $decoded = Hashids::decode($hashed_id);

    if (empty($decoded)) {
      abort(404, 'Invalid Employee ID');
    }

    $employee_id = $decoded[0];

    // Get employee record
    $employee = Employee::findOrFail($employee_id);

    // Fetch companies from AdminConfigurations
    $json = AdminConfigurations::where('key', 'companies')->value('value');
    $companies = json_decode($json, true) ?? [];

    // Check if company name is passed in query
    $companyName = $request->get('company');

    // Find company by name from query string
    $company = collect($companies)->firstWhere('name', $companyName);

    // If not found in query, fallback to employee's stored company index (optional)
    if (!$company) {
      $companyIndex = $employee->company_index ?? 0;
      $company = $companies[$companyIndex] ?? [];
    }

    // Safely extract required fields
    $company = [
      'company_name'     => $company['name']     ?? '',
      'company_address'  => $company['address']  ?? '',
      'company_location' => $company['location'] ?? '',
      'company_email'    => $company['email']    ?? '',
      'company_phone'    => $company['phone']    ?? '',
    ];

    // Fetch Aadhaar documents
    $employee_documents = EmployeeDocument::where('employee_id', $employee->id)
      ->whereIn('document_type', ['aadhaar front', 'aadhaar back'])
      ->get();

    return view('admin.employee.print_id_card', compact('employee', 'company', 'employee_documents'));
  }










}
