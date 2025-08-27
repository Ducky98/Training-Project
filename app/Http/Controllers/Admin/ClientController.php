<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Models\Client;
use App\Models\Employee;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
  protected $clientService;

  public function __construct(ClientService $clientService)
  {
    $this->clientService = $clientService;
  }
  public function index(): View|JsonResponse
  {
    if (request()->ajax()) {
      $clients = Client::select([
        'id', 'name', 'mobile_number'
      ]);

      return datatables()->of($clients)
        ->addIndexColumn()
        // Add ordering capability for the index column
        ->orderColumn('DT_RowIndex', function($query, $order) {
          $query->orderBy('id', $order);
        })
        ->addColumn('mobile_number', fn($row) => "<a href='tel:{$row->mobile_number}' style='color: #77b1f7;'>{$row->mobile_number}</a>")
        ->addColumn('action', function ($row) {
          return "<form action='" . route('admin.client.delete', $row->id) . "' method='POST' style='display:inline;' onsubmit='return confirmDelete(event)'>
            " . csrf_field() . "
            " . method_field('DELETE') . "
            <button type='submit' class='btn btn-sm btn-danger'>Delete</button>
        </form>
        <a href='" . route('admin.client.edit', $row->id) . "' class='btn btn-sm btn-primary'>Edit</a>
        <a href='" . route('admin.invoice.create', ['id' => $row->id]) . "' class='btn btn-sm btn-secondary'>Invoice</a>";

        })
        ->rawColumns(['action', 'mobile_number']) // Removed 'status' as it's not used in this table
        ->toJson();
    }

    return view('admin.client.index');
  }
  public function create():View
  {
    return view('admin.client.create');
  }
  public function edit($id): View
  {
    $client = Client::findOrFail($id);
    return view('admin.client.edit', compact('client'));
  }
  public function update(Request $request, $id)
  {
    // Validate the request
    $request->validate([
      'name' => 'required|string|max:255',
      'relationship_with_patient' => 'required|string|max:255',
      'mobile_number' => 'required|string|max:15',
      'email' => 'nullable|email|max:255',
      'emergency_contact_mobile_number' => 'required|string|max:15',
      'emergency_contact_name' => 'required|string|max:255',
      'alternate_mobile_number' => 'nullable|string|max:15',
      'gst_no' => 'nullable|string|max:20',
      'id_type' => 'required|string',
      'id_number' => 'required|string|max:50',
      'address' => 'required|string|max:255',
      'state' => 'required|string|max:100',
      'country' => 'required|string|max:100',
    ]);

    // Find the client by ID
    $client = Client::findOrFail($id);

    // Update the client data
    $client->update($request->all());

    // Redirect with a success message
    return redirect()->route('admin.client.index')->with('success', 'Client updated successfully.');
  }

  public function store(StoreClientRequest $request): RedirectResponse
  {
    // Using the ClientService to create a client
    $this->clientService->createClient($request->validated());

    // Redirect with success message
    return redirect()->route('admin.client.index')->with('success', 'Client created successfully!');
  }
  public function delete($id)
  {
    try {
      // Find the client or fail
      $client = Client::findOrFail($id);

      // Delete the client
      $client->delete();

      // Redirect back with a success message
      return redirect()->back()->with('success', 'Client deleted successfully.');
    } catch (\Exception $e) {
      // Redirect back with an error message
      return redirect()->back()->with('error', 'Failed to delete client: ' . $e->getMessage());
    }
  }


}
