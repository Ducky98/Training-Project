@php use App\Models\Employee; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Employee')

@section('content')

  <div class="row">
    <div class="col-md-12">
      <div class="mb-6">
        <form id="formAccountSettings" method="POST"
              action="{{ route('admin.employee.update.bank', $employee->employee_id) }}">
          @csrf
          @method('PUT')
          <div class="card mb-5">
            <div class="card-title p-5">
              <div class="d-flex justify-content-between">
                <h3>
                  Bank Details
                </h3>
                <a href="#" class="btn btn-outline-primary" onclick="window.history.back(); return false;">
                  <i class="ri-arrow-go-back-fill me-1"></i> Back
                </a>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row g-5">
                <!-- Bank Name -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="bank_name" name="bank_name"
                           placeholder="HDFC, SBI" value="{{ old('bank_name', $employee->bank_name) }}" />
                    <label for="bank_name">Bank Name</label>
                  </div>
                  @error('bank_name')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- Account Number -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="account_number" name="account_number"
                           placeholder="Enter Account Number carefully" value="{{ old('account_number', $employee->account_number) }}" />
                    <label for="account_number">Account Number</label>
                  </div>
                  @error('account_number')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- IFSC CODE -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="ifsc_code" name="ifsc_code"
                           placeholder="SBIN0005943" value="{{ old('ifsc_code', $employee->ifsc_code) }}" />
                    <label for="ifsc_code">Ifsc Code</label>
                  </div>
                  @error('ifsc_code')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
                <!-- IFSC CODE -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="designation" name="designation"
                           placeholder="Head Nurse" value="{{ old('designation', $employee->designation) }}" />
                    <label for="designation">Designation</label>
                  </div>
                  @error('designation')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>



              <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
              </div>
            </div>
          </div>
        </form>
        <!-- /Account -->
      </div>
    </div>
  </div>
@endsection
