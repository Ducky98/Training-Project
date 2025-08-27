@php use App\Models\Employee; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', 'Add Employee')

@section('page-script')

  @vite([
  'resources/assets/js/pages-account-settings-account.js',
  'resources/assets/js/address-select.js',
  'resources/assets/js/account-language.js', ])

@endsection

@section('content')

  <div class="row">
    <div class="col-md-12">
      <div class="card mb-6">
        <input type="hidden" name="old_country" value="{{ old('country') }}">
        <input type="hidden" name="old_state" value="{{ old('state') }}">
        <input type="hidden" name="avatar_preview" id="avatar_preview" value="{{ old('avatar_preview') }}">
        <form id="formAccountSettings" method="POST" enctype="multipart/form-data"
              action="{{ route('admin.employee.store') }}">
          @csrf

          <!-- Account -->
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-6">
              <!-- Image Preview -->
              <img src="{{ old('avatar_preview', asset('assets/img/avatars/1.png')) }}"
                   alt="user-avatar"
                   class="d-block w-px-100 h-px-100 rounded"
                   id="uploadedAvatar" />


              <div class="button-wrapper">
                <!-- Upload Button -->
                <label for="upload" class="btn btn-sm btn-primary me-3 mb-4" tabindex="0">
                  <span class="d-none d-sm-block">Upload new photo</span>
                  <i class="ri-upload-2-line d-block d-sm-none"></i>
                  <input type="file"
                         id="upload"
                         class="account-file-input"
                         name="avatar"
                         hidden
                         accept="image/png, image/jpeg, image/gif" />
                </label>

                <!-- Reset Button -->
                <button type="button" class="btn btn-sm btn-outline-danger account-image-reset mb-4">
                  <i class="ri-refresh-line d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Reset</span>
                </button>

                <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                @error('avatar')
                <small class="text-danger fw-bold">{{ $message }}</small>
                @enderror
              </div>
            </div>
          </div>
          <div class="card-body pt-0">
            <div class="row mt-1 g-5">
              <!-- First Name -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="firstName" name="firstName" placeholder="Staff First Name"
                         value="{{ old('firstName') }}" autofocus />
                  <label for="firstName">First Name<span class="text-danger fw-bolder">*</span></label>
                </div>
                @error('firstName')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <!-- Last Name -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" name="lastName" id="lastName" placeholder="Staff Last Name"
                         value="{{ old('lastName') }}" />
                  <label for="lastName">Last Name</label>
                </div>
                @error('lastName')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <!-- Father Name -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="fatherName" name="fatherName"
                         placeholder="Staff Father's Name" value="{{ old('fatherName') }}" />
                  <label for="fatherName">Father Name</label>
                </div>
                @error('fatherName')<small class="text-danger">{{ $message }}</small>@enderror
              </div>

              <!-- Mother Name -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="motherName" name="motherName"
                         placeholder="Staff Mother's Name" value="{{ old('motherName') }}" />
                  <label for="motherName">Mother Name</label>
                </div>
                @error('motherName')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <!-- Gender (Dropdown) -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <select id="gender" class="select2 form-select" name="gender">
                    <option value="">Select</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                  </select>
                  <label for="gender">Gender<span class="text-danger fw-bolder">*</span></label>
                </div>
                @error('gender')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control flatpickr"
                         type="text"
                         name="dob"
                         id="dob"
                         placeholder="Date of Birth"
                         value="{{ old('dob', $employee->dob ?? '') }}"
                         autocomplete="off" />
                  <label for="dob">Date of Birth</label>
                </div>
                @error('dob')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <script>
                document.addEventListener("DOMContentLoaded", function() {
                  flatpickr("#dob", {
                    dateFormat: "d-m-Y",
                    maxDate: "today", // Prevents selecting future dates
                    allowInput: true // Allows manual input
                  });
                });
              </script>

              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="category" name="category" placeholder="GDA/Nursing"
                          value="{{ old('category') }}" />
                  <label for="category">Category<span class="text-danger fw-bolder">*</span></label>
                </div>
                @error('category')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <!-- Mobile Number -->
              <div class="col-md-6">
                <div class="input-group input-group-merge">
                  <span class="input-group-text">+91</span>
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="mobileNumber" name="mobileNumber" class="form-control"
                           placeholder="202 555 0111" value="{{old('mobileNumber')}}" />
                    <label for="mobileNumber">Mobile No<span class="text-danger fw-bolder">*</span></label>
                  </div>
                </div>
                @error('mobileNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>
              <!-- Mobile Number -->
              <div class="col-md-6">
                <div class="input-group input-group-merge">
                  <span class="input-group-text">+91</span>
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="whatsAppNo" name="whatsAppNo" class="form-control" placeholder="202 555 0111"
                           value="{{old('whatsAppNo')}}" />
                    <label for="whatsAppNo">WhatsApp Number</label>
                  </div>
                </div>
                @error('whatsAppNo')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <!-- Alternative Mobile Number -->
              <div class="col-md-6">
                <div class="input-group input-group-merge">
                  <span class="input-group-text">+91</span>
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="altMobileNumber" name="altMobileNumber" class="form-control"
                           placeholder="202 555 0112" value="{{ old('altMobileNumber') }}" />
                    <label for="altMobileNumber">Alternative Mobile No</label>
                  </div>
                </div>
                @error('altMobileNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>
              <!-- Aadhar Number -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="aadharNumber" name="aadharNumber"
                         placeholder="1234 1234 1234" value="{{ old('aadharNumber') }}" />
                  <label for="aadharNumber">Aadhar Number</label>
                </div>
                @error('aadharNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <!-- PAN Number -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="panNumber" name="panNumber"
                         placeholder="ABCPV1234D" value="{{ old('panNumber') }}" maxlength="10"
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
                  <input class="form-control" type="text" id="kycType" name="kycType" value="{{ old('kycType') }}" />
                  <label for="kycType">KYC Type</label>
                </div>
                @error('kycType')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <!-- Police Verification Date -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="policeVerificationDate" name="policeVerificationDate"
                         value="{{ old('policeVerificationDate') }}" />
                  <label for="policeVerificationDate">Police Verification Date</label>
                </div>
                @error('policeVerificationDate')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <!-- NOK Name -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="nokName" name="nokName" value="{{ old('nokName') }}" />
                  <label for="nokName">NOK Name</label>
                </div>
                @error('nokName')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>
              <!-- NOK Number -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="nokNumber" name="nokNumber"
                         value="{{ old('nokNumber') }}" />
                  <label for="nokNumber">NOK Number</label>
                </div>
                @error('nokNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>
              <!-- Staff Family Type -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="staffFamilyType" name="staffFamilyType"
                         value="{{ old('staffFamilyType') }}" />
                  <label for="staffFamilyType">Staff Family Type</label>
                </div>
                @error('staffFamilyType')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>
              <!-- Staff Family ID -->
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="staffFamilyId" name="staffFamilyId"
                         value="{{ old('staffFamilyId') }}" />
                  <label for="staffFamilyId">Staff Family ID</label>
                </div>
                @error('staffFamilyId')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>
              <!-- Multi-Language Selection -->
              @php
                $availableLanguages = ['English', 'Hindi', 'Haryanvi', 'Bengali', 'Marathi', 'Telugu', 'Tamil', 'Gujarati', 'Urdu', 'Kannada', 'Odia', 'Malayalam', 'Punjabi', 'Mandarin', 'Spanish', 'Arabic', 'French', 'Portuguese', 'Russian', 'Japanese'];

                // Normalize stored languages to array
                $storedLanguages = $employee->languages ?? [];

                if (is_string($storedLanguages)) {
                    if (json_decode($storedLanguages)) {
                        $storedLanguages = json_decode($storedLanguages, true);
                    } else {
                        $storedLanguages = array_filter(explode(',', $storedLanguages));
                    }
                }

                // Get old input or stored values
                $oldLanguages = old('languages', $storedLanguages);

                // Ensure $oldLanguages is always an array
                if (!is_array($oldLanguages)) {
                    $oldLanguages = (array) $oldLanguages;
                }
              @endphp

              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <div class="select2-primary">
                    <select id="languages" class="form-select select2 required-field" name="languages[]" multiple data-placeholder="Select languages *">
                      @foreach($availableLanguages as $language)
                        <option value="{{ $language }}" {{ in_array($language, $oldLanguages) ? 'selected' : '' }}>
                          {{ $language }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <label for="languages"></label>
                </div>
                @error('languages')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>

              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <select id="status" class="form-select" name="status">
                    <option
                      value="{{ Employee::STATUS_INACTIVE }}" {{ old('status') == Employee::STATUS_INACTIVE ? 'selected' : '' }}>
                      Inactive
                    </option>
                    <option
                      value="{{ Employee::STATUS_READY }}" {{ old('status') == Employee::STATUS_READY ? 'selected' : '' }}>
                      Ready
                    </option>
                    <option
                      value="{{ Employee::STATUS_IN_DUTY }}" {{ old('status') == Employee::STATUS_IN_DUTY ? 'selected' : '' }}>
                      In Duty
                    </option>
                    <option
                      value="{{ Employee::STATUS_SUSPENDED }}" {{ old('status') == Employee::STATUS_SUSPENDED ? 'selected' : '' }}>
                      Suspended
                    </option>
                    <option
                      value="{{ Employee::STATUS_LEFT }}" {{ old('status') == Employee::STATUS_LEFT ? 'selected' : '' }}>
                      Left
                    </option>
                  </select>
                  <label for="status">Status<span class="text-danger fw-bolder">*</span></label>
                </div>
                @error('status')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
              </div>
              <!-- Address -->
              <div class="col-md-12">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" id="address" name="address" value="{{ old('address') }}"
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
        </form>
        <!-- /Account -->
      </div>
    </div>
  </div>
  @push('script')
    <script>
      @if(count($oldLanguages) > 0)
      $('#languages').trigger('change');
      @endif
    </script>
  @endpush
@endsection
