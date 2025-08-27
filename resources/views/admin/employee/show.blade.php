@extends('layouts/contentNavbarLayout')

@section('title', 'Employee #'.$employee->employee_id)

@section('page-script')

@endsection

@push('style')
  <style>


    .profile-photo-container {
      position: relative;
      width: 120px; /* Same as image width */
      height: 120px; /* Same as image height */
      overflow: hidden;
    }

    #profile-photo {
      width: 100%;
      height: 100%;
      display: block;
      transition: filter 0.3s ease-in-out;
    }

    .profile-photo-container:hover #profile-photo {
      filter: brightness(60%);
      cursor: pointer;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4); /* Dark overlay */
      display: flex;
      justify-content: center;
      align-items: center;
      opacity: 0;
      transition: opacity 0.3s ease-in-out;
      border-radius: .375rem;
      cursor: pointer;
    }

    .profile-photo-container:hover .overlay {
      opacity: 1;
    }

    .overlay i {
      color: white;
      font-size: 24px;
    }
  </style>
@endpush
@section('content')
  <div class="row">
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
      <!-- User Card -->
      @include('admin.employee.includes.user-card')
      <!-- /User Card -->
    </div>
    <!--/ User Sidebar -->

    <!-- User Content -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
      <!-- User Tabs -->
      @include('admin.employee.includes.tabbed-navigation')
      <!--/ User Tabs -->


      <div class="container">
        <!-- Employee Details Card -->
        <div class="card mb-4">
          <div class="card-header">
            <div class=" border-bottom  d-flex justify-content-between pb-4 mb-4">
              <h4 class="mb-0">Personal Information</h4>
              <a href="{{route('admin.employee.edit', $employee->employee_id)}}" class="btn btn-primary"><i class="ri-edit-fill"></i> Edit</a>
            </div>

          </div>
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">First Name:</div>
              <div class="col-md-8">{!! $employee->first_name ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Last Name:</div>
              <div class="col-md-8">{!! $employee->last_name ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Father's Name:</div>
              <div class="col-md-8">{!! $employee->father_name ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Mother's Name:</div>
              <div class="col-md-8">{!! $employee->mother_name ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Gender:</div>
              <div class="col-md-8">{!! ucfirst($employee->gender ?? '<i class="text-secondary">Not Available</i>') !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Date Of Birth:</div>
              <div class="col-md-8">
                {!! ucfirst($employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('d M Y') : '<i class="text-secondary">Not Available</i>') !!}
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Category:</div>
              <div class="col-md-8">{!! ucfirst($employee->category ?? '<i class="text-secondary">Not Available</i>') !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Mobile Number:</div>
              <div class="col-md-8">{!! $employee->mobile_number ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Alternate Mobile:</div>
              <div class="col-md-8">{!! $employee->alt_mobile_number ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">WhatsApp Number:</div>
              <div class="col-md-8">{!! $employee->whatsapp_number ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Email:</div>
              <div class="col-md-8">{!! $employee->email ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Language:</div>
              <div class="col-md-8">
                @php
                  // Decode JSON if stored as JSON, otherwise use as is
                  $languages = is_string($employee->languages) ? json_decode($employee->languages, true) : $employee->languages;
                  $languages = is_array($languages) ? array_filter($languages) : [];
                @endphp

                @if (!empty($languages))
                  {{ implode(', ', $languages) }}
                @else
                  <i class="text-secondary">Not Available</i>
                @endif
              </div>
            </div>

          </div>
        </div>

        <!-- Contact & Address Information Card -->
        <div class="card mb-4">
          <div class="card-header">
            <div class=" border-bottom  d-flex justify-content-between pb-4 mb-4">
              <h4 class="mb-0">ID & Address</h4>
              <a href="{{route('admin.employee.editAddress', $employee->employee_id)}}" class="btn btn-primary"><i class="ri-edit-fill"></i> Edit</a>
            </div>
          </div>
          <div class="card-body">

            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Aadhar Number:</div>
              <div class="col-md-8">{!! $employee->aadhar_number ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Next of Kin (NOK) Name:</div>
              <div class="col-md-8">{!! $employee->nok_name ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Next of Kin (NOK) Number:</div>
              <div class="col-md-8">{!! $employee->nok_number ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Staff Family ID:</div>
              <div class="col-md-8">{!! $employee->staff_family_id ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Staff Family Type:</div>
              <div class="col-md-8">{!! $employee->staff_family_type ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Address:</div>
              <div class="col-md-8">{!! $employee->address ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Alternate Address:</div>
              <div class="col-md-8">{!! $employee->alt_address ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">State:</div>
              <div class="col-md-8">{!! $employee->state ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Country:</div>
              <div class="col-md-8">{!! $employee->country ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
          </div>
        </div>
      </div>



    </div>
    <!--/ User Content -->
  </div>


@endsection
