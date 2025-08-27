@extends('layouts/contentNavbarLayout')

@section('title', 'Invoice')
@section('vendor-style')

@endsection

@section('page-script')

@endsection
@section('content')
  @php
    $companyDetails = json_decode($invoice->company_details, true);
  @endphp
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row invoice-preview">
      <!-- Invoice -->
      <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-6">
        <div class="card invoice-preview-card p-sm-12 p-lg-6">
          <div class="card-body invoice-preview-header rounded p-6 text-heading">
            <div class="container-fluid p-4">
              <div class="row g-4">
                <!-- Left Column - Invoice Title -->


                <!-- Right Column - Company Details -->
                <div class="col-12 col-md-7">
                  <!-- Company Logo and Name -->
                  <div class="d-flex flex-column flex-md-row align-items-center gap-3 mb-3 text-center text-md-start">
                    <img src="{{ asset('assets/svg/logo.svg') }}" alt="Logo" class="logo-svg" style="height: 100px; width: auto;">

                    <div>
                      <span class="fw-semibold fs-5">{{$companyDetails['company_name']}}</span>
                      <p class="mb-1">{{$companyDetails['company_address']}}</p>
                      <p class="mb-1">{{$companyDetails['company_location']}}</p>
                      <p class="mb-1"><i class="ri-mail-fill me-2"></i>{{$companyDetails['company_email']}}</p>
                      <p class="mb-1"><i class="ri-phone-fill me-2"></i>{{$companyDetails['company_phone']}}</p>
                      <p>GST No:  <b>{{$companyDetails['company_gst_number']}}</b></p>
                    </div>
                  </div>


                  <!-- Company Information Grid -->



                </div>

                <div class="col-12 col-md-5">
                  <!-- Invoice Details -->
                  <div class="mt-3 ms-3">
                    <div class="row g-2">
                      <div class="col-12">
                        <h5 class="mb-1">Invoice #{{$invoice->invoiceId}}</h5>
                      </div>
                      <div class="col-12">
                        <span class="fw-semibold">Date Issued: </span>
                        <span class="fw-bold">{{ $invoice->invoiceDate->format('d-m-Y') }}</span>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <div class="card-body px-0">
            <div class="d-flex justify-content-between px-4 flex-wrap row-gap-2">
              <div class="my-1">
                <h6 class="fw-bold">To:</h6>
                {{--                {{dd($invoice)}}--}}
                @php
                  // Decode the JSON string once and store it in a variable
                  $billingDetails = json_decode($invoice->billing_details, true);
                @endphp

                <b class="mb-0">{{ $billingDetails['client_name'] }}</b>
                <p class="mb-0"><i class="ri-phone-fill me-2"></i>{{ $billingDetails['client_contact'] }}</p>
                <p class="mb-0">{{ $billingDetails['client_address'] }}</p>
                <p class="mb-3">{{ $billingDetails['client_location'] }}</p>
                @if($invoice->gst_invoice)
                  <p class="mb-0"><b>GST No: </b>{{ $billingDetails['gst_no'] }}</p>
                @endif
              </div>
            </div>

          </div>
          <div class="table-responsive border rounded border-bottom-0">
            <table class="table m-0">
              <thead>
              <tr>
                <th>Patient Name</th>
                <th>Supervisor</th>
                <th>CG Name</th>
                <th>CG ID</th>
                <th>Shift</th>
                <th>Cost</th>
                <th>HSN/SAC Code</th>
                <th>No of Day</th>
                <th>Total</th>
              </tr>
              </thead>
              <tbody>


              @if($invoice->items->isEmpty())
                <tr>
                  <td colspan="6" class="text-center">No record found.</td>
                </tr>
              @else
                @foreach($invoice->items as $item)
                  <tr>
                    <td class="text-nowrap text-heading">{{ optional($item)->name ?? 'N/A' }}</td>
                    <td class="text-nowrap">{{ optional($item)->supervisor ?? 'N/A' }}</td>
                    <td class="text-nowrap">{{ optional($item)->cg_name ?? 'N/A' }}</td>
                    <td>{{ optional($item)->cg_id ?? 'N/A' }}</td>
                    <td>{{ optional($item)->shift ?? 'N/A' }}</td>
                    <td>₹{{ number_format(optional($item)->cost ?? 0, 2) }}</td>
                    <td>{{ optional($item)->code ?? 'N/A' }}</td>
                    <td>{{ optional($item)->days ?? 'N/A' }}</td>
                    <td>₹{{ number_format(optional($item)->total ?? 0, 2) }}</td>
                  </tr>
                @endforeach
              @endif

              </tbody>
            </table>
          </div>

          <div class="table-responsive">
            <table class="table m-0 table-borderless">
              <tbody>
              <tr>
                <td class="align-top pe-6 ps-0 py-6">

                </td>
                <td class="px-0 py-6 w-px-100">
                  <p class="mb-1">Subtotal:</p>
                  <p class="mb-2 border-bottom pb-2">Tax:</p>
                  <p class="mb-2 border-bottom pb-2">Discount:</p>
                  <p class="mb-0">Total:</p>
                </td>
                <td class="text-end px-0 py-6 w-px-100">
                  <p class="fw-medium text-heading mb-1">₹{{ number_format($subtotal, 2) }}</p>

                  <p class="fw-medium text-heading mb-2 border-bottom pb-2">{{ number_format($invoice->tax_rate, 2) }} %</p>
                  <p class="fw-medium text-heading mb-2 border-bottom pb-2">- ₹{{ number_format($invoice->discount, 2) }}</p>
                  <p class="fw-medium text-heading mb-0">₹{{ number_format($total, 2) }}</p>
                </td>
              </tr>
              </tbody>
            </table>
          </div>

          <hr class="mt-0 mb-6">

          <div class="card-body p-0">
            <div class="row">
              <div class="col-md-6 col-12">
                <div><b>Bank Name: </b> {{$companyDetails['company_bank_name']}}</div>
                <div><b>A/C No: </b> {{$companyDetails['company_bank_account_number']}}</div>
                <div class="mb-1"><b>IFSC Code : </b> {{$companyDetails['company_ifsc_code']}}</div>
                @if($invoice->gst_invoice)
                  <div class="border-top pt-2"><b>GST No : </b> {{$companyDetails['company_gst_number']}}</div>
                @endif
              </div>
              <div class="col-md-6 col-12 text-end position-relative" style="min-height: 100px;">
                <div class="position-absolute end-0">
                  <b>Authorised Signatory</b>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /Invoice -->

      <!-- Invoice Actions -->
      <div class="col-xl-3 col-md-4 col-12 invoice-actions">
        <div class="card">
          <div class="card-body">
            {{--            <button class="btn btn-primary d-grid w-100 mb-4 waves-effect waves-light" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">--}}
            {{--              <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ri-send-plane-line ri-16px scaleX-n1-rtl me-1_5"></i>Send Invoice</span>--}}
            {{--            </button>--}}

            <div class="d-flex mb-4">
              <a class="btn btn-outline-secondary d-grid w-100 waves-effect" target="_blank" href="{{route('admin.invoice.print', $invoice->id)}}">
                Print
              </a>

            </div>

          </div>
        </div>
      </div>
      <!-- /Invoice Actions -->
    </div>

  </div>
@endsection
