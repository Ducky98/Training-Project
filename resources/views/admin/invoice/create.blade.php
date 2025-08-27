@extends('layouts/contentNavbarLayout')

@section('title', 'New Invoice')

@section('vendor-style')

@endsection

@section('page-script')
  @vite(['resources/js/invoice.js'])
@endsection

@section('content')
  <section>
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.invoice.store') }}" method="POST" id="invoice-form">
      @csrf
      <div class="row invoice-edit">
        <div class="col-lg-9 col-12">
          <div class="card invoice-preview-card p-6">
            <div class="card-body invoice-preview-header rounded p-6">
              <div class="row">
                <div class="col-md-7">
                  <div class="d-flex align-items-center gap-3">
                <span>
                  <img src="{{ asset('assets/svg/logo.svg') }}" alt="Logo" class="logo-svg" width="100px">
                </span>
                    <select id="company-select" class="form-select mb-3">
                      @foreach ($config as $index => $company)
                        <option value="{{ $index }}">{{ $company['name'] }}</option>
                      @endforeach
                    </select>
                  </div>
{{--                  <p>{{$config['company_address']}}</p>--}}
                </div>
                <div class="col-md-5 col-12 pe-0 ps-0 ps-md-2">
                  <dl class="row mb-0 gx-4">
                    <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                      <span class="h5 text-capitalize mb-0 text-nowrap">Invoice</span>
                    </dt>
                    <dd class="col-sm-7">
                      <div class="input-group input-group-sm input-group-merge disabled">
                        <span class="input-group-text">#</span>
                        <input type="text" class="form-control" id="invoiceId" readonly style="background: #f2f2f3; color:#8a8a8a;" placeholder="74909" name="invoiceId" value="{{$invoice_no }}" >
                      </div>
                    </dd>
                    <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                      <span class="fw-normal text-nowrap">Date Issued:</span>
                    </dt>
                    <dd class="col-sm-7">
                      <input type="text" name="invoiceDate" id="invoiceDate" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}" readonly>
                    </dd>

                  </dl>
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h6>Billing Details</h6>
                  <div class="mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" name="client_name" value="{{ $client ? $client->name : '' }}">
                  </div>
                  <div class="mb-3">
                    <label>Address</label>
                    <input type="text" class="form-control" name="client_address" value="{{ $client ? $client->address : '' }}">
                  </div>
                  <div class="mb-3">
                    <label>Location</label>
                    <input type="text" class="form-control" name="client_location" value="{{ $client ? $client->state.', '.$client->country  : '' }}">
                  </div>
                  <div class="mb-3">
                    <label>Contact</label>
                    <input type="text" class="form-control" name="client_contact" value="{{ $client ? $client->mobile_number : '' }}">
                  </div>
                  <div class="mb-3" id="gstField">
                    <label>GSTIN / UIN</label>
                    <input type="text" class="form-control" name="client_gst_no" value="{{ $client ? $client->gst_no : '' }}">
                  </div>
                </div>

                <div class="col-md-6">
                  <h6>Invoice Period</h6>
                  <div class="mb-3">
                    <label>Period</label>
                    <input type="text" id="date_range" name="date_range" class="form-control" value="">
                  </div>
                  <div class="my-3" id="tax-field">
                    <label>Tax %</label>
                    <input type="number" id="tax" name="tax_rate" class="form-control" value="">
                  </div>
                  <div class="my-3">
                    <label>Discount</label>
                    <input type="number" id="discount" name="discount" class="form-control" value="">
                  </div>
                </div>
              </div>
            </div>

            <hr>
            <div class="card-body">
              <h6>Service Type</h6>
              <div id="invoice-items">
                <div class="invoice-item" style="display: none">
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="name[]" placeholder="Patient Name" disabled>
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control item-cost" name="cost[]" placeholder="Cost per Day" disabled>
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control item-qty" name="days[]" placeholder="Days" disabled>
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control item-total" name="total[]" placeholder="Total" readonly disabled>
                    </div>
                    <div class="col-md-1">
                      <button class="btn btn-danger remove-item">X</button>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <input type="text"
                             class="form-control cg-name"
                             name="cg_name[]"
                             placeholder="CG Name"
                             list="employeeNames" disabled>
                      <datalist id="employeeNames">
                        @foreach($employees as $employee)
                          <option value="{{ $employee->first_name }} {{ $employee->last_name }}"
                                  data-id="{{ $employee->employee_id }}">
                        @endforeach
                      </datalist>
                    </div>
                    <div class="col-md-3">
                      <input type="text"
                             class="form-control cg-id"
                             name="cg_id[]"
                             placeholder="CG ID"
                             list="employeeIds" disabled>
                      <datalist id="employeeIds" >
                        @foreach($employees as $employee)
                          <option value="{{ $employee->employee_id }}"
                                  data-name="{{ $employee->first_name }} {{ $employee->last_name }}">
                        @endforeach
                      </datalist>
                    </div>
                  </div>
                  <hr>
                </div>


                <hr class="mt-4">
              </div>
              <button id="add-item" type="button" class="btn btn-primary">Add Item</button>

              <hr>
              <div class="row">
                <div class="col-md-6 offset-md-6">
                  <div class="d-flex justify-content-between total_show">
                    <span>Subtotal:</span>
                    <span></span>
                  </div>
                  <div class="d-flex justify-content-between">
                    <span>Tax:</span>
                    <span></span>
                  </div>
                  <hr><div class="d-flex justify-content-between">
                    <span>Discount:</span>
                    <span></span>
                  </div>
                  <hr>
                  <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span></span>
                  </div>
                </div>
              </div>
            </div>

            <hr>

          </div>
        </div>

        <div class="col-lg-3">
          <div class="card">

            <div class="card-body text-center">
              <button id="generate-invoice"  type="submit" class="btn btn-primary w-100">Generate Invoice</button>
            </div>
          </div>
          <div class="p-4">
            <h6>Options</h6>
            <div class="form-check form-switch">
              <label class="form-check-label" for="includeGST">Include GST</label>
              <input type="checkbox" class="form-check-input" checked name="include_gst" id="includeGST">
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
  @push('scripts')
    <script>


      document.getElementById('invoice-form').addEventListener('submit', function (e) {
        e.preventDefault();

        // Create form data
        const formData = {
          companyIndex : document.getElementById('company-select').value,
          invoice_no: document.getElementById('invoiceId').value,
          invoice_date: document.getElementById('invoiceDate').value,
          date_range: document.getElementById('date_range').value,
          name: document.querySelector('input[name="client_name"]').value,
          client_address: document.querySelector('input[name="client_address"]').value,
          client_location: document.querySelector('input[name="client_location"]').value,
          client_contact: document.querySelector('input[name="client_contact"]').value,
          client_gst_no: document.querySelector('input[name="client_gst_no"]').value,
          tax_rate: document.getElementById('tax').value,
          include_gst: document.getElementById('includeGST').checked,
          discount: document.getElementById('discount').value,
          items: []
        };
        console.log(formData)
        // Get all visible invoice items
        document.querySelectorAll('.invoice-item').forEach(item => {
          if (window.getComputedStyle(item).display !== 'none') {
            formData.items.push({
              name: item.querySelector('input[name="name[]"]').value || '',
              cost: item.querySelector('input[name="cost[]"]').value || 0,
              days: item.querySelector('input[name="days[]"]').value || 0,
              total: item.querySelector('input[name="total[]"]').value || 0,
              cg_name: item.querySelector('input[name="cg_name[]"]').value || '',
              cg_id: item.querySelector('input[name="cg_id[]"]').value || '',
              supervisor: item.querySelector('input[name="supervisor[]"]').value || '',
              shift: item.querySelector('input[name="shift[]"]').value || '',
              code: item.querySelector('input[name="code[]"]').value || ''
            });
          }
        });
        fetch("{{ route('admin.invoice.store') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(formData)
        })
                .then(response => {
                  // Check if the response is ok (status 200-299)
                  if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                  }
                  return response.json();
                })
                .then(data => {
                  console.log('Success response:', data); // Debug log
                  if (data.success) {
                    alert(data.message || "Invoice created successfully!");
                    window.location.href = data.redirect || "{{ route('admin.invoice.index') }}";
                  } else {
                    alert(data.message || "An error occurred.");
                  }
                })
                .catch(error => {
                  console.error("Error details:", error);
                  alert("Something went wrong! See console for details.");
                });
      });


    </script>
  @endpush
@endsection
