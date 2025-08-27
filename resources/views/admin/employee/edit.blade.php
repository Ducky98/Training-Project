@php use App\Models\Employee; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', 'Add Employee')

@section('page-script')

  @vite(['resources/assets/js/account-language.js'])

@endsection

@section('content')

  <div class="row">
    <div class="col-md-12">
      <div class="mb-6">
        <form id="formAccountSettings" method="POST"
              action="{{ route('admin.employee.update', $employee->employee_id) }}">
          @csrf
          @method('PUT')
          <div class="card mb-5">
            <div class="card-title p-5">
              <div class="d-flex justify-content-between">
                <h3>
                  Basic Details:
                </h3>
                <a href="#" class="btn btn-outline-primary" onclick="window.history.back(); return false;">
                  <i class="ri-arrow-go-back-fill me-1"></i> Back
                </a>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row g-5">
                <!-- First Name -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="firstName" name="firstName"
                           placeholder="Staff First Name" value="{{ old('firstName',$employee->first_name) }}"
                    />
                    <label for="firstName">First Name<span class="text-danger fw-bolder">*</span></label>
                  </div>
                  @error('firstName')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- Last Name -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" name="lastName" id="lastName" placeholder="Staff Last Name"
                           value="{{ old('lastName',$employee->last_name) }}" />
                    <label for="lastName">Last Name</label>
                  </div>
                  @error('lastName')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- Father Name -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="fatherName" name="fatherName"
                           placeholder="Staff Father's Name" value="{{ old('fatherName',$employee->father_name) }}" />
                    <label for="fatherName">Father Name</label>
                  </div>
                  @error('fatherName')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <!-- Mother Name -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="motherName" name="motherName"
                           placeholder="Staff Mother's Name" value="{{ old('motherName',$employee->mother_name) }}" />
                    <label for="motherName">Mother Name</label>
                  </div>
                  @error('motherName')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- Gender (Dropdown) -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <select id="gender" class="select2 form-select" name="gender">
                      <option value="">Select</option>
                      <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male
                      </option>
                      <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>
                        Female
                      </option>
                      <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other
                      </option>
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

                <!-- Status -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <select id="status" class="form-select" name="status">
                      <option
                        value="{{ Employee::STATUS_INACTIVE }}" {{ old('status', $employee->status) == Employee::STATUS_INACTIVE ? 'selected' : '' }}>
                        Inactive
                      </option>
                      <option
                        value="{{ Employee::STATUS_READY }}" {{ old('status', $employee->status) == Employee::STATUS_READY ? 'selected' : '' }}>
                        Ready
                      </option>
                      <option
                        value="{{ Employee::STATUS_IN_DUTY }}" {{ old('status', $employee->status) == Employee::STATUS_IN_DUTY ? 'selected' : '' }}>
                        In Duty
                      </option>
                      <option
                        value="{{ Employee::STATUS_SUSPENDED }}" {{ old('status', $employee->status) == Employee::STATUS_SUSPENDED ? 'selected' : '' }}>
                        Suspended
                      </option>
                      <option
                        value="{{ Employee::STATUS_LEFT }}" {{ old('status', $employee->status) == Employee::STATUS_LEFT ? 'selected' : '' }}>
                        Left
                      </option>
                    </select>
                    <label for="status">Status<span class="text-danger fw-bolder">*</span></label>
                  </div>
                  @error('status')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
                <!-- Category -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="category" name="category"
                           placeholder="Staff Category" value="{{ old('category',$employee->category) }}"
                    />
                    <label for="category">Category<span class="text-danger fw-bolder">*</span></label>
                  </div>
                  @error('category')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
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
              </div>
            </div>

            <div class="card-title p-5">
              <h3>
                Contact Details:
              </h3>
            </div>
            <div class="card-body pt-0">
              <div class="row g-5">

                <!-- Mobile Number -->
                <div class="col-md-6">
                  <div class="input-group input-group-merge">
                    <span class="input-group-text">+91</span>
                    <div class="form-floating form-floating-outline">
                      <input type="text" id="mobileNumber" name="mobileNumber" class="form-control"
                             placeholder="202 555 0111" value="{{old('mobileNumber', $employee->mobile_number)}}" />
                      <label for="mobileNumber">Mobile No<span class="text-danger fw-bolder">*</span></label>
                    </div>
                  </div>
                  @error('mobileNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
                <!-- Whatsapp Number -->
                <div class="col-md-6">
                  <div class="input-group input-group-merge">
                    <span class="input-group-text">+91</span>
                    <div class="form-floating form-floating-outline">
                      <input type="text" id="whatsAppNo" name="whatsAppNo" class="form-control"
                             placeholder="202 555 0111" value="{{old('whatsAppNo', $employee->whatsapp_number)}}" />
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
                             placeholder="202 555 0112" value="{{ old('altMobileNumber', $employee->alt_mobile_number) }}" />
                      <label for="altMobileNumber">Alternative Mobile No</label>
                    </div>
                  </div>
                  @error('altMobileNumber')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>

                <!-- Email -->
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="email" id="email" name="email"
                           placeholder="Staff Email Id" value="{{ old('email',$employee->email) }}" />
                    <label for="email">Email</label>
                  </div>
                  @error('email')<small class="text-danger fw-bold">{{ $message }}</small>@enderror
                </div>
                <div class="mt-8">
                  <button type="submit" class="btn btn-primary me-3">Save changes</button>
                  <button type="reset" class="btn btn-outline-secondary">Reset</button>
                </div>
              </div>
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
