@extends('layouts/contentNavbarLayout')

@section('title', 'New Clients')

@section('vendor-style')
@endsection

@section('page-script')
  @vite(['resources/assets/js/address-select.js'])
@endsection

@section('content')
  <div class="row">
    <div class="col-xl-12">
      <div class="card mb-6">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Create Client</h5>
          <small class="text-body float-end">Fill in client details</small>
        </div>
        <div class="card-body">
          <input type="hidden" name="old_country" value="{{ old('country') }}">
          <input type="hidden" name="old_state" value="{{ old('state') }}">
          <form action="{{ route('admin.client.store') }}" method="POST">
            @csrf

            <div class="row g-4">
              {{-- Full Name --}}
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="John Doe" value="{{ old('name') }}" />
                  <label for="name">Full Name<span class="text-danger fw-bolder">*</span></label>
                  @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Relationship with Patient --}}
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="relationship_with_patient" name="relationship_with_patient" class="form-control @error('relationship_with_patient') is-invalid @enderror" placeholder="Father, Mother, Sibling, etc." value="{{ old('relationship_with_patient') }}" />
                  <label for="relationship_with_patient">Relationship with Patient<span class="text-danger fw-bolder">*</span></label>
                  @error('relationship_with_patient') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Mobile Number --}}
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="mobile_number" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" placeholder="658 799 8941" value="{{ old('mobile_number') }}" />
                  <label for="mobile_number">Mobile Number No<span class="text-danger fw-bolder">*</span></label>
                  @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
              {{-- Email --}}
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="example@email.com" value="{{ old('email') }}" />
                  <label for="email">Email</label>
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Emergency Contact Mobile Number --}}
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="emergency_contact_mobile_number" name="emergency_contact_mobile_number" class="form-control @error('emergency_contact_mobile_number') is-invalid @enderror" placeholder="Emergency Contact Number" value="{{ old('emergency_contact_mobile_number') }}" />
                  <label for="emergency_contact_mobile_number">Emergency Contact Mobile Number<span class="text-danger fw-bolder">*</span></label>
                  @error('emergency_contact_mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>


              {{-- Emergency Contact Name --}}
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" placeholder="Emergency Contact Name" value="{{ old('emergency_contact_name') }}" />
                  <label for="emergency_contact_name">Emergency Contact Name<span class="text-danger fw-bolder">*</span></label>
                  @error('emergency_contact_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Alternate Mobile Number --}}
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="alternate_mobile_number" name="alternate_mobile_number" class="form-control @error('alternate_mobile_number') is-invalid @enderror" placeholder="Alternate Mobile Number" value="{{ old('alternate_mobile_number') }}"/>
                  <label for="alternate_mobile_number">Alternate Mobile Number</label>
                  @error('alternate_mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- GST Number --}}
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="gst_no" name="gst_no" class="form-control @error('gst_no') is-invalid @enderror" placeholder="GST Number" value="{{ old('gst_no') }}"/>
                  <label for="gst_no">GST Number</label>
                  @error('gst_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <!-- ID Type -->
              <div class="col-md-6">
                <div class="form-floating">
                  <select id="id_type" name="id_type" class="form-select @error('id_type') is-invalid @enderror">
                    @foreach(\App\Models\Client::getIdTypes() as $key => $label)
                      <option value="{{ $key }}" {{ old('id_type', 'aadhar') == $key ? 'selected' : '' }}>
                        {{ $label }}
                      </option>
                    @endforeach
                  </select>
                  <label for="id_type">ID Type</label>
                  @error('id_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>




              <!-- ID Number -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="id_number" name="id_number" class="form-control @error('id_number') is-invalid @enderror" placeholder="Enter ID Number" value="{{ old('id_number') }}" />
                  <label for="id_number">ID Number<span class="text-danger fw-bolder">*</span></label>
                  @error('id_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
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

            {{-- Submit Button --}}
            <div class="text-end mt-4">
              <button type="submit" class="btn btn-primary">Save Client</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
