@php use App\Models\Employee; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Employee')

@section('page-script')

  @vite([  'resources/assets/js/address-select.js',])

@endsection

@section('content')

  <div class="row">
    <div class="col-md-12">
      <div class="mb-6">
        <input type="hidden" name="old_country" value="{{ old('country',$employee->country) }}">
        <input type="hidden" name="old_state" value="{{ old('state',$employee->state) }}">
        <form id="formAccountSettings" method="POST"
              action="{{ route('admin.employee.updateAddress', $employee->employee_id) }}">
          @csrf
          @method('PUT')
          <div class="card mb-5">
            <div class="card-title p-5">
              <div class="d-flex justify-content-between">
                <h3>
                  Id & Address Details:
                </h3>
                <a href="#" class="btn btn-outline-primary" onclick="window.history.back(); return false;">
                  <i class="ri-arrow-go-back-fill me-1"></i> Back
                </a>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row g-5">
                <!-- Aadhar Number -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="aadharNumber" name="aadharNumber"
                           placeholder="1234 1234 1234" value="{{ old('aadharNumber', $employee->aadhar_number) }}" />
                    <label for="aadharNumber">Aadhar Number</label>
                  </div>
                  @error('aadharNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- PAN Number -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="panNumber" name="panNumber"
                           placeholder="ABCPV1234D" value="{{ old('panNumber', $employee->pan_number) }}" maxlength="10"
                           oninput="formatPanNumber(this)" />
                    <label for="panNumber">PAN Number</label>
                  </div>
                  @error('panNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <script>
                  function formatPanNumber(input) {
                    input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 10);
                  }
                </script>

                <!-- KYC Type -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="kycType" name="kycType" value="{{ old('kycType', $employee->kyc_type) }}" />
                    <label for="kycType">KYC Type</label>
                  </div>
                  @error('kycType')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- Police Verification Date -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="policeVerificationDate" name="policeVerificationDate"
                           value="{{ old('policeVerificationDate', optional($employee->police_verification_date)->format('d-m-Y')) }}" />

                    <label for="policeVerificationDate">Police Verification Date</label>
                  </div>
                  @error('policeVerificationDate')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- NOK Name -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="nokName" name="nokName" value="{{ old('nokName', $employee->nok_name) }}" />
                    <label for="nokName">NOK Name</label>
                  </div>
                  @error('nokName')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
                <!-- NOK Number -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="nokNumber" name="nokNumber"
                           value="{{ old('nokNumber', $employee->nok_number) }}" />
                    <label for="nokNumber">NOK Number</label>
                  </div>
                  @error('nokNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
                <!-- Staff Family Type -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="staffFamilyType" name="staffFamilyType"
                           value="{{ old('staffFamilyType', $employee->staff_family_type) }}" />
                    <label for="staffFamilyType">Staff Family Type</label>
                  </div>
                  @error('staffFamilyType')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
                <!-- Staff Family ID -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="staffFamilyId" name="staffFamilyId"
                           value="{{ old('staffFamilyId', $employee->staff_family_id) }}" />
                    <label for="staffFamilyId">Staff Family ID</label>
                  </div>
                  @error('staffFamilyId')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
              </div>

              <div class="row mt-1 g-5">

                <div class="col-md-12">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="alt_address" name="alt_address" value="{{ old('alt_address',$employee->alt_address) }}"
                           placeholder="Enter Alternative Address" />
                    <label for="alt_address">Alternative Address </label>
                  </div>
                  @error('alt_address')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
                <!-- Address -->
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="address" name="address" value="{{ old('address',$employee->address) }}"
                           placeholder="Enter Address" />
                    <label for="address">Address<span class="text-danger fw-bolder">*</span> </label>
                  </div>
                  @error('address')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>


                <!-- State Dropdown (Disabled by default) -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <select id="state" class="form-select select2" name="state" disabled>
                      <option value="">Select State</option>
                    </select>
                    <label for="state">State<span class="text-danger fw-bolder">*</span></label>
                  </div>
                  @error('state')<small class="text-danger fw-bold">{{ $message }}</small>@enderror

                </div>
                <!-- Country Dropdown -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <select id="country" class="form-select select2" name="country">
                      <option value="">Select Country</option>
                    </select>
                    <label for="country">Country<span class="text-danger fw-bolder">*</span></label>
                  </div>
                  @error('country')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
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
