<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice #{{$invoice->invoiceId}} (Print version)</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @include('layouts/sections/styles')

  <style>
    @media print {
      @page {
        margin: 0.5cm;
        size: A4;
      }

      body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }

      .card {
        border: none !important;
        box-shadow: none !important;
      }

      .invoice-actions {
        display: none !important;
      }

      .container-xxl {
        max-width: 100% !important;
        padding: 0 !important;
      }

      .invoice-preview-card {
        padding: 0 !important;
      }



      /* Adjust the invoice container height to better fit A4 page */
      .invoice-container {
        position: relative;
        min-height: 27.7cm; /* Slightly reduced to prevent overflow */
        max-height: 27.7cm;
        display: flex;
        flex-direction: column;
        border: 2px solid black !important;
        page-break-inside: avoid;
        margin-bottom: 0.5cm; /* Add space between pages */
      }

      /* Strengthen page break controls */
      .invoice-page {
        page-break-after: always;
        page-break-before: auto;
      }

      .invoice-page:last-child {
        page-break-after: avoid !important;
      }

      /* Ensure table rows don't split across pages */
      tr {
        page-break-inside: avoid;
      }

    }

    body {
      font-family: "Times New Roman", serif !important;
      margin: 0;
      padding: 0;
    }

    * {
      color: black !important;
    }

    /* Improved print layout */
    .invoice-container {
      position: relative;
      min-height: 28.7cm; /* Slightly reduced from A4 height to ensure no overflow */
      max-height: 28.7cm; /* Match min-height */
      display: flex;
      flex-direction: column;
      border: 2px solid black !important;
      page-break-inside: avoid;
    }

    .main-content {
      flex: 1;
    }

    .footer-section {
      width: 100%;
      padding: 10px 10px 10px 0px;
      margin-top: auto;
      page-break-before: avoid;
      page-break-inside: avoid;
    }

    /* Reduced margins and paddings */
    p {
      margin-bottom: 0.25rem;
    }

    .compact-table th,
    .compact-table td {
      padding: 0.25rem 0.5rem;
    }

    /* Additional fixes to prevent overflow */
    .table {
      width: 100%;
      margin-bottom: 0 !important;
    }

    /* Adjust the container padding */
    .card-body {
      padding: 1rem !important;
    }

    /* Continuation page header */
    .continuation-header {
      text-align: center;
      margin-bottom: 10px;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container-xxl flex-grow-1">
  <div class="row invoice-preview">
    <!-- Calculate how many pages we need -->
    @php
      $itemsPerPage = 10;
      $totalItems = count($invoice->items);
      $totalPages = ceil($totalItems / $itemsPerPage);
    @endphp

      <!-- Loop through each page -->
    @for ($page = 0; $page < $totalPages; $page++)
      <div class="col-12 invoice-page">
        <div class="card invoice-preview-card">
          <div class="card-body p-4">
            <div class="invoice-container">
              <!-- Main Content Section -->
              <div class="main-content">
                <!-- Header (Only full header on first page) -->
                @if($page == 0)
                  <div class="d-flex justify-content-between px-2">
                    <h5 class="mb-0"><b>@if($invoice->include_gst === 1) GSTIN/UIN: - {{$companyDetails['company_gst_number']}} @endif</b></h5>
                    @php
                      $cinNo = $companyDetails['cin_no'] ?? null;

                      if (empty($cinNo)) {
                          $cinNo = \App\Models\AdminConfigurations::where('key', 'cin_no')->value('value');
                      }
                    @endphp

                    <span class="ms-auto">
                        @if($invoice->include_gst === 1 && !empty($cinNo))
                                            CIN NO: - {{ $cinNo }}
                                          @endif
                    </span>

                  </div>
                  <div class="d-flex justify-content-center position-relative p-2 border-bottom border-2 border-dark">
                    <h3 class="mb-0"><b>@if($invoice->include_gst === 1) TAX @endif INVOICE</b></h3>
                  </div>

                  <div class="border-dark border-2 border-bottom p-1">
                    <center><h3 class="fw-bolder mb-0" style='text-transform: uppercase'>{{$companyDetails['company_name']}}</h3></center>
                    <center><h5 class="mb-0">{{$companyDetails['company_address']}}, {{$companyDetails['company_location']}}</h5></center>
                    <center><h5 class="mb-0">Contact Us: - {{$companyDetails['company_phone']}}</h5></center>
                    <center><h5 class="mb-0">E-Mail: - {{$companyDetails['company_email']}}</h5></center>
                  </div>

                  <!-- Client & Invoice Details -->
                  <div class="border-dark border-2 border-bottom">
                    <div class="row">
                      <div class="col-6 pe-0" style="border-right: 2px solid black">
                        <h5 class="border-dark border-2 border-bottom ps-2 pt-1 mb-0">Billed To:</h5>
                        <div class="px-2 py-1">
                          <p><strong>Name:</strong> {{ $billingDetails['client_name'] ?? 'N/A' }}</p>
                          <p><strong>Address:</strong> {{ $billingDetails['client_address'], $billingDetails['client_location'] ?? 'N/A'  }}</p>
                          <p><strong>Contact:</strong> {{ $billingDetails['client_contact'] ?? 'N/A'  }}</p>
                          @if($invoice->include_gst)<p><strong>GSTIN/UIN:</strong> {{ $billingDetails['client_gst_no'] ?? 'N/A'}}</p>@endif
                        </div>
                      </div>
                      <div class="col-6 ps-0">
                        <h5 class="border-dark border-2 border-bottom pt-1 ps-2 mb-0">Invoice Detail:</h5>
                        <div class="px-2 py-1">
                          <p><strong>Invoice No:</strong> {{$invoice->invoiceId}}</p>
                          <p><strong>Invoice Date:</strong> {{ $invoice->invoiceDate->format('d-m-Y') }}</p>
                          <p><strong>Period:</strong> {{ $invoice->date_range }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                @else
                  <!-- Simplified header for continuation pages -->
                  <div class="border-dark border-2 border-bottom p-2">
                    <center><h3 class="fw-bolder mb-0" style='text-transform: uppercase'>{{$companyDetails['company_name']}}</h3></center>
                    <div class="continuation-header">
                      <p>Invoice #{{$invoice->invoiceId}} - Continuation Page {{$page + 1}} of {{$totalPages}}</p>
                    </div>
                  </div>
                @endif

                <!-- Items Table -->
                @if($invoice->include_gst === 1)
                  <table class="table table-bordered border-2 border-dark compact-table" style="margin-bottom: 0; padding: 0;">
                    <thead class="table-light">
                    <tr>
                      <th>S.N.</th>
                      <th>Description of Goods</th>
                      <th>HSN/SAC Code</th>
                      <th>Qty.</th>
                      <th>Unit</th>
                      <th>Price</th>
                      <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                      $startIndex = $page * $itemsPerPage;
                      $endIndex = min(($page + 1) * $itemsPerPage, $totalItems);
                    @endphp

                    @for ($i = $startIndex; $i < $endIndex; $i++)
                      @php $item = $invoice->items[$i]; @endphp
                      <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ optional($item)->name ?? 'N/A' }}</td>
                        <td>{{ optional($item)->code ?? 'N/A' }}</td>
                        <td>{{ optional($item)->days ?? 'N/A' }}</td>
                        <td>{{ optional($item)->shift ?? 'N/A' }}</td>
                        <td>₹{{ number_format(optional($item)->cost ?? 0, 2) }}</td>
                        <td>₹{{ number_format(optional($item)->total ?? 0, 2) }}</td>
                      </tr>
                    @endfor

                    <!-- Fill remaining empty rows -->
                    @for ($i = $endIndex - $startIndex; $i < $itemsPerPage; $i++)
                      <tr class="empty-row">
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    @endfor
                    </tbody>
                  </table>
                @else
                  <!-- Your original table when GST is NOT included -->
                  <table class="table table-bordered border-2 border-dark compact-table" style="margin-bottom: 0; padding: 0;">
                    <thead class="table-light">
                    <tr>
                      <th>S.No</th>
                      <th>Staff ID</th>
                      <th>Staff Name</th>
                      <th>Patient Name</th>
                      <th>Supervisor</th>
                      <th>Shift</th>
                      <th>Price</th>
                      <th>HSN/SAC Code</th>
                      <th>Total Duty</th>
                      <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                      $startIndex = $page * $itemsPerPage;
                      $endIndex = min(($page + 1) * $itemsPerPage, $totalItems);
                    @endphp

                    @for ($i = $startIndex; $i < $endIndex; $i++)
                      @php $item = $invoice->items[$i]; @endphp
                      <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ optional($item)->cg_id ?? 'N/A' }}</td>
                        <td>{{ optional($item)->cg_name ?? 'N/A' }}</td>
                        <td>{{ optional($item)->name ?? 'N/A' }}</td>
                        <td>{{ optional($item)->supervisor ?? 'N/A' }}</td>
                        <td>{{ optional($item)->shift ?? 'N/A' }}</td>
                        <td>₹{{ number_format(optional($item)->cost ?? 0, 2) }}</td>
                        <td>{{ optional($item)->code ?? 'N/A' }}</td>
                        <td>{{ optional($item)->days ?? 'N/A' }}</td>
                        <td>₹{{ number_format(optional($item)->total ?? 0, 2) }}</td>
                      </tr>
                    @endfor

                    <!-- Fill remaining empty rows -->
                    @for ($i = $endIndex - $startIndex; $i < $itemsPerPage; $i++)
                      <tr class="empty-row">
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    @endfor
                    </tbody>
                  </table>
                @endif


                <!-- Bank Details and Totals - Only on the last page -->
                @if($page == $totalPages - 1)
                  <div class="row mx-0 mt-0 border-bottom border-dark border-2">
                    <div class="col-8 ">
                      <div class="row px-0">
                        <div class="col-12 col-md-8">
                          <h6 class="fw-bolder mb-1">Please Pay by NEFT/RTGS/Bank Transfer Only</h6>
                          <p class="mb-1"><strong>Bank Name: </strong>{{$companyDetails['company_bank_name']}}</p>
                          <p class="mb-1"><strong>A/C No: </strong>{{$companyDetails['company_bank_account_number']}}</p>
                          <p class="mb-1"><strong>IFSC Code: </strong>{{$companyDetails['company_ifsc_code']}}</p>
                          @if($invoice->include_gst === 1)
                            <p class="mb-0"><strong>GST No: </strong>{{$companyDetails['company_gst_number']}}</p>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-4 pe-0">
                      <table class="table table-borderless compact-table">
                        <tbody>
                        <tr class="border-2 border-dark border-top border-end border-bottom">
                          <td>Subtotal:</td>
                          <td class="text-end">₹{{ number_format($subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->include_gst === 1)
                          <tr class="border-2 border-dark border-top border-end border-bottom">
                            <td>CGST {{ number_format($cgstRate, 2) }}%:</td>
                            <td class="text-end">₹{{ number_format($cgstAmount, 2) }}</td>
                          </tr>
                          <tr class="border-2 border-dark border-top border-end border-bottom">
                            <td>SGST {{ number_format($sgstRate, 2) }}%:</td>
                            <td class="text-end">₹{{ number_format($sgstAmount, 2) }}</td>
                          </tr>
                          {{--                          <tr class="border-2 border-dark border-top border-end border-bottom">--}}
                          {{--                            <td>Tax:</td>--}}
                          {{--                            <td class="text-end">{{ number_format($invoice->tax_rate, 2) }}%</td>--}}
                          {{--                          </tr>--}}
                        @endif

                        @if(!($invoice->include_gst === 1))
                          <tr class="border-2 border-dark border-top border-end border-bottom">
                            <td>Advance Payment:</td>
                            <td class="text-end">- ₹{{ number_format($invoice->discount, 2) }}</td>
                          </tr>
                        @endif
                        <tr class="fw-bold border-2 border-dark border-top border-end ">
                          <td>Total:</td>
                          <td class="text-end">₹{{ number_format($total, 2) }}</td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                @elseif($page < $totalPages - 1)
                  <!-- For non-last pages, add a "Continued on next page" note -->
                  <div class="text-center mt-3">
                    <p><em>Continued on next page...</em></p>
                  </div>
                @endif
              </div>

              <!-- Footer Section - Only on the last page -->
              @if($page == $totalPages - 1)
                <div class="footer-section">
                  <div style="display: grid; grid-template-columns: 1fr 1fr; align-items: end;">
                    <div>
                      <strong>Terms & Conditions</strong><br>
                      E.& O.E.<br>
                      1. Goods once sold will not be taken back.<br>
                      2. Interest @ 18% p.a. will be charged if the payment is not made within the stipulated time.<br>
                      3. Subject to 'Delhi' Jurisdiction only.
                    </div>
                    <div class="text-end">
                      <div style="min-height: 40px;"></div>
                      <div class="border-bottom w-50 ms-auto">This is a computer-generated invoice. No signature required.</div>
                      <p class="mt-1"><strong>Authorised Signatory</strong></p>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    @endfor
  </div>
</div>

<script>
  window.onload = function() {
    window.print();
  };
</script>
</body>
</html>

