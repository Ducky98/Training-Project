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

  <div class="row invoice-edit">
    <!-- Invoice Edit-->
    <div class="col-lg-9 col-12 mb-lg-0 mb-6">
      <div class="card invoice-preview-card p-sm-12 p-6">
        <div class="card-body invoice-preview-header rounded p-6 px-3 text-heading">
          <div class="row mx-0 px-3">
            <div class="col-md-7 mb-md-0 mb-6 ps-0">
              <div class="d-flex svg-illustration align-items-center gap-3 mb-6">
                <span class="app-brand-logo demo">
                  <span style="color:#9055FD;">
                   <img src="{{ asset('assets/svg/logo.svg') }}" alt="Logo" class="logo-svg" >
                  </span>
                </span>

                <span class="mb-0 app-brand-text fw-semibold">{{$config['company_name']}}</span>
              </div>
              <p>{{$config['company_address']}}</p>
              {{--              <p class="mb-1">Office 149, 450 South Brand Brooklyn</p>--}}
              {{--              <p class="mb-1">San Diego County, CA 91905, USA</p>--}}
              {{--              <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>--}}
            </div>
            <div class="col-md-5 col-12 pe-0 ps-0 ps-md-2">
              <dl class="row mb-0 gx-4">
                <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                  <span class="h5 text-capitalize mb-0 text-nowrap">Invoice</span>
                </dt>
                <dd class="col-sm-7">
                  <div class="input-group input-group-sm input-group-merge disabled">
                    <span class="input-group-text">#</span>
                    <input type="text" class="form-control" disabled="" placeholder="74909" value="{{$invoice_no }}" id="invoiceId">
                  </div>
                </dd>
                <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                  <span class="fw-normal text-nowrap">Date Issued:</span>
                </dt>
                <dd class="col-sm-7">
                  <input type="text" id="invoiceDate" class="form-control form-control-sm invoice-date flatpickr-input" placeholder="DD-MM-YYYY" wire:model="date_of_issue" readonly="readonly">
                </dd>
                <dt class="col-sm-5 d-md-flex align-items-center justify-content-start">
                  <span class="fw-normal">Place of Supply</span>
                </dt>
                <dd class="col-sm-7 mb-0">
                  <input type="text" class="form-control form-control-sm" placeholder="Haryana" wire:model="palceOfSupply" >
                </dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="card-body px-0">
          <div class="row my-1">
            <div class="col-md-6 col-sm-5 col-12 mb-sm-0 mb-6">
              <h6>To:</h6>
              <div class="row">
                <div class="client-info">
                  <div class="form-floating form-floating-outline mb-6">
                    <input type="text" class="form-control" id="name" value="{{ $billing->name ?? '' }}"  wire:model="billing.name" placeholder="" />
                    <label for="name">Name</label>
                  </div>
                  <div class="form-floating form-floating-outline mb-6">
                    <input type="text" class="form-control" id="mobile" value="{{ $billing->contact ?? '' }}"  wire:model="billing.contact" placeholder="" />
                    <label for="mobile">Phone</label>
                  </div>
                  <div class="form-floating form-floating-outline mb-6">
                    <input type="text" class="form-control" id="address" value="{{ $billing->address ?? '' }}"  wire:model="billing.address" placeholder="" />
                    <label for="address">Address</label>
                  </div>
                  <div class="form-floating form-floating-outline mb-6">
                    <input type="text" class="form-control" id="location" value="{{ $billing->location ?? '' }}" wire:model="billing.location" placeholder="Enter city, state, country" />
                    <label for="location">Location</label>
                  </div>

                @if($showGSTSections)
                    <div class="form-floating form-floating-outline mb-6">
                      <input type="text" class="form-control" id="gst_no" value="{{ $billing->gst_no ?? '' }}"  wire:model="billing.gst_no" placeholder="" />
                      <label for="gst_no">GSTIN / UIN</label>
                    </div>
                  @endif
                </div>
              </div>


            </div>
            <div class="col-md-6 col-sm-5 col-12 mb-sm-0 mb-6">
              <h6>Period:</h6>
              <div class="row">
                <div class="client-info">
                  <div class="form-floating form-floating-outline mb-6">
                    <input type="text" id="date_range" class="form-control" wire:model="date_range">
                    <label for="date_range">Period</label>
                  </div>
                </div>
              </div>

              <p><strong>Selected Period:</strong> {{ $from_date }} to {{ $to_date }}</p>

              <script>
                document.addEventListener('DOMContentLoaded', function () {
                  flatpickr("#date_range", {
                    mode: "range",
                    dateFormat: "d-m-Y",
                    onClose: function(selectedDates, dateStr) {
                    @this.set('date_range', dateStr.replace(" to ", " to "));
                    }
                  });
                });
              </script>



            </div>
          </div>
        </div>
        <hr class="mb-6 mt-1">
        <div class="card-body pt-0 px-0">
          @foreach($items as $index => $item)
            <div class="repeater-wrapper pt-0 pt-md-9">
              <div class="d-flex border rounded position-relative pe-0">
                <div class="row w-100 p-5 gx-5">
                  <div class="col-md-6 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Item</h6>
                    <input type="text" class="form-control item-details invoice-item-price mb-5" wire:model.lazy="items.{{ $index }}.title"/>
                    <textarea class="form-control" rows="2" wire:model.lazy="items.{{ $index }}.description"></textarea>
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-5">
                    <h6 class="h6 repeater-title">Cost (₹)</h6>
                    <input type="number" class="form-control invoice-item-price mb-5" wire:model.lazy="items.{{ $index }}.cost">
                    <div class="d-flex flex-column justify-content-evenly">

                      <div class="d-flex ">
                        <span class="fw-bold" style="white-space: nowrap;">Total:<span class="discount fw-normal ms-1 me-2">₹{{ number_format($item['total'], 2) }}</span></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-4">
                    <h6 class="h6 repeater-title">Qty</h6>
                    <input type="number" class="form-control invoice-item-qty" wire:model.lazy="items.{{ $index }}.quantity">
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-4">
                    <h6 class="h6 repeater-title">Tax (%)</h6>
                    <input type="number" class="form-control invoice-item-qty" wire:model.lazy="items.{{ $index }}.tax">
                  </div>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                  @if(count($items) > 1)
                    <button wire:click="removeItem({{ $index }})" class="btn p-0">
                      <i class="ri-close-line"></i>
                    </button>
                  @else
                    <button class="btn p-0" disabled>
                      <i class="ri-close-line"></i>
                    </button>
                  @endif

                </div>
              </div>
            </div>
          @endforeach
          <div class="row my-4" >
            <div class="col-12">
              <button wire:click="addItem" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Item
              </button>
            </div>
          </div>
          <hr class="my-3">
          <div class="card-body px-0">
            <div class="row">

              <div class=" col-12 d-flex justify-content-md-end mt-2">
                <div class="col-md-6 col-12 offset-md-6" >
                  <div class="invoice-calculations">
                    <div class="d-flex justify-content-between mb-2">
                      <span>Subtotal:</span>
                      <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                      <span>Tax:</span>
                      <span>₹{{ number_format($totalTax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                      <span class="fw-bold">Total:</span>
                      <span class="fw-bold">₹{{ number_format($total, 2) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /Invoice Edit-->

    </div>
    <!-- Invoice Actions -->
    <div class="col-lg-3 col-12 invoice-actions">
      <div class="card mb-6">
        <div class="card-body">
          <div class="d-flex">
            <button wire:click="generateInvoice" class="btn btn-primary d-grid w-100 mb-4 waves-effect waves-light">
              <span class="d-flex align-items-center justify-content-center text-white text-nowrap">Generate Invoice</span>
            </button>


          </div>
        </div>
      </div>
      <div>

        <div class="d-flex justify-content-between mb-3">
          <label for="includeGST" class="mb-0"> Include GST NO</label>
          <div class="form-check form-switch mb-0 me-n2">
            <input type="checkbox" class="form-check-input" wire:model="includeGST" wire:change="includeGSTSection" id="includeGST">
          </div>
        </div>

      </div>
    </div>
    <!-- /Invoice Actions -->


  </div>
</section>
