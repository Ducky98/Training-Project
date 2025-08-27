<?php

namespace App\Livewire;

use App\Models\Client;
use App\Services\AdminConfigService;
use Livewire\Component;

class InvoiceClient extends Component
{
  public $config;
  public $invoice_no;
  public $date_of_issue;
  public $sameDetails = false;
  public $includeGST = false;
  public $showGSTSections = false;
  public $palceOfSupply = "Haryana";
  public $clientId;
  public $client;
  public $date_range;
  public $from_date;
  public $to_date;

  public function updatedDateRange($value)
  {
    $dates = explode(" to ", $value);

    if (count($dates) === 2) {
      $this->from_date = \Carbon\Carbon::parse($dates[0])->format('Y-m-d');
      $this->to_date = \Carbon\Carbon::parse($dates[1])->format('Y-m-d');
    }
  }



  public $billing = [
    'name' => '',
    'contact' => '',
    'address' => '',
    'location' => '',
    'gst_no' => '',
  ];


  public $items = [];
  public $subtotal = 0;
  public $totalTax = 0;
  public $total = 0;

  protected AdminConfigService $configService;

  public function mount($clientId = null)
  {
    $this->date_of_issue = now()->format('d-m-Y');
    $this->invoice_no = $this->generateInvoiceNo();
    $this->configService = new AdminConfigService();
    $this->config = $this->configService->getInvoiceConfig();
    if ($clientId) {
      $this->client = Client::find($clientId, ['id', 'name', 'phone', 'address']);

      if ($this->client) {
        $this->billing = [
          'name' => $this->client->name,
          'contact' => $this->client->phone,
          'address' => $this->client->address,
          'location' => '',
          'gst_no' => '',
        ];
      }
    }

    $this->resetItems();
  }
  private function generateInvoiceNo()
  {
    $date = now()->format('Ymd');
    $lastInvoiceId = (\App\Models\Invoice::max('id') ?? 0) + 1;

    // Keep only the last 4 digits
    $nextId = str_pad($lastInvoiceId % 10000, 4, '0', STR_PAD_LEFT);

    $random = strtoupper(substr(md5(uniqid()), 0, 4));

    return "{$date}-{$nextId}-{$random}";
  }


  private function resetItems()
  {
    $this->items = [$this->createEmptyItem(1)];
  }

  public function includeGSTSection()
  {
    if ($this->includeGST) {
      $this->showGSTSections = true; // Show GST fields
    } else {
      $this->showGSTSections = false; // Hide GST fields
      $this->billing['gst_no'] = '';  // Clear GST numbers when disabled
    }
  }

  private function createEmptyItem($id)
  {
    return [
      'id' => $id,
      'title' => '',
      'description' => '',
      'cost' => 0,
      'quantity' => 1,
      'tax' => 0,
      'total' => 0,
    ];
  }

  protected $rules = [
    'billing.name' => 'required|string|max:255',
    'billing.contact' => 'required|string|max:20',
    'billing.address' => 'required|string|max:500',
    'billing.location' => 'required|string|max:100',
    'billing.gst_no' => 'nullable|string|max:50',
    'date_range' => 'required|string',
    'from_date' => 'required|date',
    'to_date' => 'required|date|after_or_equal:from_date',

    'items.*.title' => 'required|string|max:255',
    'items.*.description' => 'required|string|max:255',
    'items.*.cost' => 'numeric|min:0',
    'items.*.quantity' => 'numeric|min:1',
    'items.*.tax' => 'numeric|min:0',
  ];

  public function updated($propertyName)
  {
    $this->validateOnly($propertyName);
  }

  public function addItem()
  {
    $newId = count($this->items) + 1;
    $this->items[] = $this->createEmptyItem($newId);
    $this->calculateTotals();
  }

  public function removeItem($index)
  {
    unset($this->items[$index]);
    $this->items = array_values($this->items); // Reindex array
    $this->calculateTotals();
  }

  public function updatedItems()
  {
    $this->calculateTotals();
  }

  private function calculateTotals()
  {
    $this->subtotal = collect($this->items)
      ->sum(fn($item) => $item['cost'] * $item['quantity']);

    $this->totalTax = collect($this->items)->sum(function ($item) {
      $itemSubtotal = floatval($item['cost']) * floatval($item['quantity']);
      return $itemSubtotal * (floatval($item['tax']) / 100);
    });

    $this->total = $this->subtotal + $this->totalTax;

    // Update individual item totals
    $this->items = collect($this->items)->map(function ($item) {
      $itemSubtotal = floatval($item['cost']) * floatval($item['quantity']);
      $taxAmount = $itemSubtotal * (floatval($item['tax']) / 100);
      $item['total'] = $itemSubtotal + $taxAmount;
      return $item;
    })->toArray();
  }

  public function render()
  {
    return view('livewire.invoice-client');
  }
  public function generateInvoice()
  {
    $this->validate([
      'billing.name' => 'required|string',
      'billing.contact' => 'required|string',
      'billing.address' => 'required|string',
      'billing.location' => 'required|string',
      'date_range' => 'required|string',
      'from_date' => 'required|date',
      'to_date' => 'required|date|after_or_equal:from_date',
      'items' => 'required|array|min:1',
      'items.*.title' => 'required|string',
      'items.*.cost' => 'required|numeric|min:0',
      'items.*.quantity' => 'required|integer|min:1',
      'items.*.tax' => 'nullable|numeric|min:0',
      'items.*.description' => 'nullable|string|max:255',
      'total' => 'required|numeric|min:0',
    ]);

    $invoice = \App\Models\Invoice::create([
      'invoice_number' => $this->invoice_no,
      'gst_invoice' => $this->includeGST,
      'date_of_issue' => now()->format('d-m-Y'),
      'place_of_supply' => $this->palceOfSupply,
      'billing_details' => json_encode($this->billing),
      'items' => json_encode($this->items),
      'subtotal' => $this->subtotal,
      'total_tax' => $this->totalTax,
      'total' => $this->total,
      'company_detail' => $this->config,
      'from_date' => $this->from_date,
      'to_date' => $this->to_date,
    ]);

    session()->flash('message', 'Invoice Generated Successfully');
    return redirect()->route('admin.invoice.show', $invoice->id);
  }


}
