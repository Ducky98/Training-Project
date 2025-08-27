<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Invoice;
use App\Services\AdminConfigService;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;


class InvoiceController extends Controller
{
  protected $configService;
  protected $invoiceService;

  public function __construct(AdminConfigService $configService, InvoiceService $invoiceService)
  {
    $this->configService = $configService;
    $this->invoiceService = $invoiceService;
  }
  public function index(): View|JsonResponse
  {
    if (request()->ajax()) {
      $invoices = Invoice::select(['id', 'invoiceId', 'billing_details', 'invoiceDate']);

      return datatables()->of($invoices)
        ->addIndexColumn()
        ->addColumn('invoiceDate', function ($row) {
          // Check if invoiceDate is null before formatting
          return $row->invoiceDate ? $row->invoiceDate->format('d M Y') : '';
        })
        ->addColumn('client_name', function ($row) {
          $billingDetails = json_decode($row->billing_details, true);
          return $billingDetails['client_name'] ?? 'N/A';
        })
        ->addColumn('action', function ($row) {
          $showUrl = route('admin.invoice.show', $row->id);
          $deleteUrl = route('admin.invoice.delete', $row->id);

          $btn = '<a href="' . $showUrl . '" class="btn btn-sm btn-primary">View</a>';

          $btn .= '<form action="' . $deleteUrl . '" method="POST" style="display:inline-block; margin-left:5px;">'
            . csrf_field()
            . method_field('DELETE')
            . '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this invoice?\')">Delete</button>'
            . '</form>';

          return $btn;
        })

        ->rawColumns(['action'])
        ->toJson();
    }

    return view('admin.invoice.index');
  }
  public function create($id = null)
  {
    $config = $this->configService->getAllInvoiceConfigs();
    $invoice_no = $this->invoiceService->generateInvoiceNo();
    $client = $id ? Client::findOrFail($id) : null; // Check if ID is provided
    $employees = Employee::select(['id', 'employee_id', 'first_name', 'last_name'])->get();

    return view('admin.invoice.create', compact('client', 'config', 'invoice_no', 'employees'));
  }




  public function store(Request $request)
  {
//    Log::info($request);
    try {
      DB::beginTransaction();

      // Get company details
      $companyDetails = $this->invoiceService->getCompanyDetails($request->companyIndex);
      $billingDetails = json_encode([
        'client_name' => $request->name,
        'client_address' => $request->client_address,
        'client_location' => $request->client_location,
        'client_contact' => $request->client_contact,
        'client_gst_no' => $request->client_gst_no,
      ]);
//      Log::info($request->include_gst);
      // Create Invoice
      $invoice = $this->invoiceService->createInvoice([
        'invoiceId' => $request->invoice_no,
        'invoiceDate' => $request->invoice_date,
        'company_details' => json_encode($companyDetails),
        'billing_details' => $billingDetails,
        'date_range' => $request->date_range,
        'tax_rate' => $request->tax_rate ?? 0,
        'discount' => $request->discount ?? 0,
        'include_gst' => $request->include_gst,
      ]);

      // Transform items into a properly structured array
      $invoiceItems = [];
      if (!empty($request->items)) {
        foreach ($request->items as $item) {
          $invoiceItems[] = [
            'invoice_id' => $invoice->id,
            'name' => $item['name'] ?? null,
            'cg_name' => $item['cg_name'] ?? null,
            'cg_id' => $item['cg_id'] ?? null,
            'supervisor' => $item['supervisor'] ?? null,
            'shift' => $item['shift'] ?? null,
            'code' => $item['code'] ?? null,
            'cost' => $item['cost'] ?? 0,
            'days' => $item['days'] ?? 0,
            'total' => $item['total'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
          ];
        }
      }

      $this->invoiceService->addInvoiceItems($invoice, $invoiceItems);

      DB::commit();

      // Always return JSON response
      return response()->json([
        'success' => true,
        'redirect' => route('admin.invoice.index'),
        'message' => 'Invoice created successfully.',
      ]);

    } catch (\Exception $e) {
      DB::rollBack();
      \Log::error('Invoice creation error: ' . $e->getMessage());

      return response()->json([
        'success' => false,
        'message' => env('APP_DEBUG') ? $e->getMessage() : 'An unexpected error occurred.',
        'error_code' => $e->getCode()
      ], 500);
    }
  }
  public function delete(Invoice $invoice)
  {
    $invoice->delete();

    return redirect()->back()->with('success', 'Invoice deleted successfully.');
  }



  public function show($id): View
  {
    $invoice = Invoice::with('items')->findOrFail($id);

    // Calculate subtotal: sum of all item totals
    $subtotal = $invoice->items->sum('total');

    // Calculate total: (subtotal * tax_rate / 100) - discount
    $total = ($subtotal * ($invoice->tax_rate / 100)) + $subtotal - $invoice->discount;

    return view('admin.invoice.show', compact('invoice', 'subtotal', 'total'));
  }



  public function print($id){
    $invoice = Invoice::with('items')->findOrFail($id);

    // Calculate subtotal: sum of all item totals
    $subtotal = $invoice->items->sum('total');

    // Calculate CGST and SGST amounts (each is half of the total tax)
    $cgstRate = $invoice->include_gst ? 9 : 0;
    $sgstRate = $invoice->include_gst ? 9 : 0;

    $cgstAmount = $invoice->include_gst ? ($subtotal * ($cgstRate / 100)) : 0;
    $sgstAmount = $invoice->include_gst ? ($subtotal * ($sgstRate / 100)) : 0;
    $tax = $invoice->include_gst ? ($subtotal * ($invoice->tax_rate / 100)) : 0;
    // Check if it's a GST invoice
    if ($invoice->include_gst) {
      // For GST invoice, total = subtotal + tax (no discount applied)
      $total = $subtotal + $cgstAmount + $sgstAmount;
    } else {
      // For non-GST invoice, apply discount
      $total = $subtotal - $invoice->discount;
    }

    $companyDetails = json_decode($invoice->company_details, true) ?? [];
    $billingDetails = json_decode($invoice->billing_details, true) ?? [];

    return view('admin.invoice.print', compact('invoice', 'subtotal', 'total', 'companyDetails', 'billingDetails', 'cgstRate', 'sgstRate', 'cgstAmount', 'sgstAmount'));
  }

}
