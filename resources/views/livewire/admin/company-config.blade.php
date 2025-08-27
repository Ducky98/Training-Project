<section>


<div class="card shadow-sm border-0 mb-6">
  <div class="card-header bg-gradient-primary text-white py-3">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <i class="ri-building-line ri-24px me-3"></i>
        <h5 class="mb-0 fw-semibold">Company Configuration</h5>
      </div>
      <div class="d-flex gap-2">
        @if ($isEditing)
          <button type="button" class="btn btn-outline-light btn-sm" wire:click="cancelEdit">
            <i class="ri-close-line ri-16px me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-light btn-sm fw-semibold" wire:click="save">
            <i class="ri-save-line ri-16px me-1"></i>Save Changes
          </button>
        @else
          <button wire:click="edit" class="btn btn-light btn-sm fw-semibold">
            <i class="ri-edit-2-line ri-16px me-1"></i>Edit Configuration
          </button>
        @endif
      </div>
    </div>
  </div>

  <div class="card-body p-4">
    {{-- Flash Messages --}}
    @if (session()->has('message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-check-circle-line ri-16px me-2"></i>
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if (session()->has('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line ri-16px me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    {{-- Company Selection --}}
    <div class="mb-4 p-3 bg-light rounded-3">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label fw-semibold text-muted mb-0">
          <i class="ri-building-4-line ri-16px me-2"></i>Select Company
        </label>
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-outline-primary" wire:click="addNewCompany" title="Add New Company">
            <i class="ri-add-line ri-14px"></i>
          </button>
          @if(count($companies) > 1)
            <button type="button" class="btn btn-outline-danger" wire:click="deleteCompany"
                    onclick="return confirm('Are you sure you want to delete this company?')" title="Delete Company">
              <i class="ri-delete-bin-line ri-14px"></i>
            </button>
          @endif
        </div>
      </div>
      <select class="form-select form-select-lg shadow-sm" wire:model.live="selectedCompanyIndex">
        @foreach ($companies as $index => $company)
          <option value="{{ $index }}">
            {{ $company['name'] ?? 'Company ' . ($index + 1) }}
          </option>
        @endforeach
      </select>
      <small class="text-muted mt-1 d-block">
        <i class="ri-information-line ri-12px me-1"></i>
        Total Companies: {{ count($companies) }}
      </small>
    </div>

    {{-- Edit Mode --}}
    @if ($isEditing)
      <form wire:submit.prevent="save" class="needs-validation" novalidate>
        {{-- Company Information Section --}}
        <div class="mb-4">
          <h6 class="text-primary fw-semibold mb-3 border-bottom pb-2">
            <i class="ri-information-line ri-16px me-2"></i>Company Information
          </h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Company Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-lg shadow-sm"
                     wire:model="company_name"
                     placeholder="Enter company name"
                     required />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">CIN Number</label>
              <input type="text" class="form-control form-control-lg shadow-sm"
                     wire:model="cin_no"
                     placeholder="Corporate Identification Number" />
            </div>
          </div>
        </div>

        {{-- Address Section --}}
        <div class="mb-4">
          <h6 class="text-primary fw-semibold mb-3 border-bottom pb-2">
            <i class="ri-map-pin-line ri-16px me-2"></i>Address Details
          </h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Street Address</label>
              <input type="text" class="form-control form-control-lg shadow-sm"
                     wire:model="company_address"
                     placeholder="Street address" />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">City/Location</label>
              <input type="text" class="form-control form-control-lg shadow-sm"
                     wire:model="company_location"
                     placeholder="City, State" />
            </div>
          </div>
        </div>

        {{-- Banking Information Section --}}
        <div class="mb-4">
          <h6 class="text-primary fw-semibold mb-3 border-bottom pb-2">
            <i class="ri-bank-line ri-16px me-2"></i>Banking Information
          </h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-medium">Bank Name</label>
              <input type="text" class="form-control form-control-lg shadow-sm"
                     wire:model="company_bank_name"
                     placeholder="Bank name" />
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Account Number</label>
              <input type="text" class="form-control form-control-lg shadow-sm"
                     wire:model="company_bank_account_number"
                     placeholder="Account number" />
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">IFSC Code</label>
              <input type="text" class="form-control form-control-lg shadow-sm"
                     wire:model="company_ifsc_code"
                     placeholder="IFSC code" />
            </div>
          </div>
        </div>

        {{-- Contact & Tax Information Section --}}
        <div class="mb-4">
          <h6 class="text-primary fw-semibold mb-3 border-bottom pb-2">
            <i class="ri-contacts-line ri-16px me-2"></i>Contact & Tax Information
          </h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-medium">Email Address</label>
              <input type="email" class="form-control form-control-lg shadow-sm"
                     wire:model="company_email"
                     placeholder="company@example.com" />
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Phone Number</label>
              <input type="tel" class="form-control form-control-lg shadow-sm"
                     wire:model="company_phone"
                     placeholder="+91 XXXXX XXXXX" />
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">GST Number</label>
              <input type="text" class="form-control form-control-lg shadow-sm"
                     wire:model="company_gst_number"
                     placeholder="GST number" />
            </div>
          </div>
        </div>

        {{-- Save Button at Bottom --}}
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
          <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">
            <i class="ri-close-line ri-16px me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-primary btn-lg px-4">
            <i class="ri-save-line ri-16px me-1"></i>Save Configuration
          </button>
        </div>
      </form>
    @else
      {{-- View Mode --}}
      <div class="row g-4">
        {{-- Company Information Card --}}
        <div class="col-lg-6">
          <div class="card border-0 bg-light h-100">
            <div class="card-header bg-primary text-white py-2">
              <h6 class="mb-0 fw-semibold">
                <i class="ri-information-line ri-16px me-2"></i>Company Information
              </h6>
            </div>
            <div class="card-body p-3">
              <div class="info-item mb-3">
                <small class="text-muted text-uppercase fw-semibold">Company Name</small>
                <div class="fw-medium">{{ $company_name ?: 'Not specified' }}</div>
              </div>
              <div class="info-item mb-3">
                <small class="text-muted text-uppercase fw-semibold">CIN Number</small>
                <div class="fw-medium">{{ $cin_no ?: 'Not specified' }}</div>
              </div>
              <div class="info-item mb-3">
                <small class="text-muted text-uppercase fw-semibold">GST Number</small>
                <div class="fw-medium">{{ $company_gst_number ?: 'Not specified' }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Address Card --}}
        <div class="col-lg-6">
          <div class="card border-0 bg-light h-100">
            <div class="card-header bg-success text-white py-2">
              <h6 class="mb-0 fw-semibold">
                <i class="ri-map-pin-line ri-16px me-2"></i>Address Details
              </h6>
            </div>
            <div class="card-body p-3">
              <div class="info-item mb-3">
                <small class="text-muted text-uppercase fw-semibold">Street Address</small>
                <div class="fw-medium">{{ $company_address ?: 'Not specified' }}</div>
              </div>
              <div class="info-item">
                <small class="text-muted text-uppercase fw-semibold">City/Location</small>
                <div class="fw-medium">{{ $company_location ?: 'Not specified' }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Banking Information Card --}}
        <div class="col-lg-6">
          <div class="card border-0 bg-light h-100">
            <div class="card-header bg-info text-white py-2">
              <h6 class="mb-0 fw-semibold">
                <i class="ri-bank-line ri-16px me-2"></i>Banking Information
              </h6>
            </div>
            <div class="card-body p-3">
              <div class="info-item mb-3">
                <small class="text-muted text-uppercase fw-semibold">Bank Name</small>
                <div class="fw-medium">{{ $company_bank_name ?: 'Not specified' }}</div>
              </div>
              <div class="info-item mb-3">
                <small class="text-muted text-uppercase fw-semibold">Account Number</small>
                <div class="fw-medium">{{ $company_bank_account_number ?: 'Not specified' }}</div>
              </div>
              <div class="info-item">
                <small class="text-muted text-uppercase fw-semibold">IFSC Code</small>
                <div class="fw-medium">{{ $company_ifsc_code ?: 'Not specified' }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Contact Information Card --}}
        <div class="col-lg-6">
          <div class="card border-0 bg-light h-100">
            <div class="card-header bg-warning text-white py-2">
              <h6 class="mb-0 fw-semibold">
                <i class="ri-contacts-line ri-16px me-2"></i>Contact Information
              </h6>
            </div>
            <div class="card-body p-3">
              <div class="info-item mb-3">
                <small class="text-muted text-uppercase fw-semibold">Email Address</small>
                <div class="fw-medium">
                  @if($company_email)
                    <a href="mailto:{{ $company_email }}" class="text-decoration-none">
                      {{ $company_email }}
                    </a>
                  @else
                    Not specified
                  @endif
                </div>
              </div>
              <div class="info-item">
                <small class="text-muted text-uppercase fw-semibold">Phone Number</small>
                <div class="fw-medium">
                  @if($company_phone)
                    <a href="tel:{{ $company_phone }}" class="text-decoration-none">
                      {{ $company_phone }}
                    </a>
                  @else
                    Not specified
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  {{-- Keyboard Shortcuts --}}
  <div class="card-footer bg-light border-0 py-2">
    <small class="text-muted">
      <i class="ri-keyboard-line ri-14px me-1"></i>
      Keyboard shortcuts: <kbd>Ctrl + S</kbd> to save
    </small>
  </div>

  {{-- Enhanced Save with Ctrl+S --}}
  <script>
    document.addEventListener('keydown', function(e) {
      if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
      @this.call('save');

        // Show toast notification
        const toastHtml = `
          <div class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3"
               role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
            <div class="d-flex">
              <div class="toast-body">
                <i class="ri-check-line me-2"></i>Configuration saved successfully!
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto"
                      data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        `;

        document.body.insertAdjacentHTML('beforeend', toastHtml);
        const toast = new bootstrap.Toast(document.querySelector('.toast:last-child'));
        toast.show();

        // Remove toast after it's hidden
        setTimeout(() => {
          const toastEl = document.querySelector('.toast:last-child');
          if (toastEl) toastEl.remove();
        }, 5000);
      }
    });
  </script>

  {{-- Custom Styles --}}
  <style>
    .bg-gradient-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .info-item {
      border-left: 3px solid #667eea;
      padding-left: 12px;
    }

    .card {
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn {
      transition: all 0.2s ease;
    }

    .btn:hover {
      transform: translateY(-1px);
    }

    kbd {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 3px;
      padding: 2px 4px;
      font-size: 0.875em;
      color: black;
      font-weight: bold;
    }
  </style>
</div>
</section>
