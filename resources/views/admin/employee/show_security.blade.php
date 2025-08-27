@extends('layouts/contentNavbarLayout')

@section('title', 'Employee #'.$employee->employee_id)

@section('page-script')

@endsection
@section('vendor-style')
  <script>
    // Define variables in Blade and pass them to JavaScript
    const deleteEmployeeUrl = "{{ route('admin.employee.delete', ['employee_id' => $employee->employee_id]) }}";
    const employeeIndexUrl = "{{ route('admin.employee.index') }}";
    const csrfToken = "{{ csrf_token() }}";
  </script>
  @vite(['resources/assets/js/delete-employee.js'])
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

        <div class="card mb-6">
          <h5 class="card-header">Delete Account</h5>
          <div class="card-body">
            <form id="formAccountDeactivation" onsubmit="return false" class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
              <div class="form-check mb-6 ms-3">
                <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation">
                <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <button type="submit" class="btn btn-danger deactivate-account waves-effect waves-light">Deactivate Account</button>
              <input type="hidden"></form>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-header">
            <div class=" border-bottom  d-flex justify-content-between pb-4 mb-4">
              <h4 class="mb-0">Bank Details</h4>
              <a href="{{route('admin.employee.edit.bank', $employee->employee_id)}}" class="btn btn-primary"><i class="ri-edit-fill"></i> Edit</a>
            </div>

          </div>
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Bank Name:</div>
              <div class="col-md-8">{!! $employee->bank_name ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Account Number:</div>
              <div class="col-md-8">{!! $employee->account_number ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">IFSC Code:</div>
              <div class="col-md-8">{!! $employee->ifsc_code ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4 fw-bold">Designation:</div>
              <div class="col-md-8">{!! $employee->designation ?? '<i class="text-secondary">Not Available</i>' !!}</div>
            </div>



          </div>
        </div>
        <!--
        <div class="card mb-6">
          <h5 class="card-header">Change Password</h5>
          <div class="card-body">
            <form id="formChangePassword" method="POST" onsubmit="return false" class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
              <div class="alert alert-warning alert-dismissible" role="alert">
                <h5 class="alert-heading mb-1">Ensure that these requirements are met</h5>
                <span>Minimum 8 characters long, uppercase &amp; symbol</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row gx-5">
                <div class="mb-4 col-12 col-sm-6 form-password-toggle fv-plugins-icon-container">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="············">
                      <label for="newPassword">New Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                  </div>
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
                <div class="mb-4 col-12 col-sm-6 form-password-toggle fv-plugins-icon-container">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="············">
                      <label for="confirmPassword">Confirm New Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                  </div>
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
                <div>
                  <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Change Password</button>
                </div>
              </div>
              <input type="hidden"></form>
          </div>
        </div>


        <div class="card mb-6">
          <div class="card-header">
            <h5 class="mb-0">Two-steps verification</h5>
            <span class="card-subtitle mt-0">Keep your account secure with authentication step.</span>
          </div>
          <div class="card-body pt-0">
            <h6 class="mb-1">SMS</h6>
            <div class="mb-4">
              <div class="d-flex w-100 action-icons">
                <input id="defaultInput" class="form-control form-control-sm me-5" type="text" placeholder="+1(968) 945-8832">
                <a href="javascript:;" class="btn btn-icon btn-outline-secondary me-2 waves-effect" data-bs-target="#enableOTP" data-bs-toggle="modal"><i class="ri-edit-box-line ri-22px"></i></a>
                <a href="javascript:;" class="btn btn-icon btn-outline-secondary waves-effect"><i class="ri-user-add-line"></i></a>
              </div>
            </div>
            <p class="mb-0">Two-factor authentication adds an additional layer of security to your account by requiring more than just a password to log in.
              <a href="javascript:void(0);" class="text-primary">Learn more.</a>
            </p>
          </div>
        </div>


        <div class="card mb-6">
          <h5 class="card-header">Recent Devices</h5>
          <div class="table-responsive table-border-bottom-0">
            <table class="table">
              <thead>
              <tr>
                <th class="text-truncate">Browser</th>
                <th class="text-truncate">Device</th>
                <th class="text-truncate">Location</th>
                <th class="text-truncate">Recent Activities</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td class="text-truncate"><img src="https://demos.themeselection.com/materio-bootstrap-html-laravel-admin-template/demo/assets/img/icons/brands/chrome.png" alt="Chrome" class="me-4" width="22" height="22"><span class="text-heading">Chrome on Windows</span></td>
                <td class="text-truncate">HP Spectre 360</td>
                <td class="text-truncate">Switzerland</td>
                <td class="text-truncate">10, July 2021 20:07</td>
              </tr>
              <tr>
                <td class="text-truncate"><img src="https://demos.themeselection.com/materio-bootstrap-html-laravel-admin-template/demo/assets/img/icons/brands/chrome.png" alt="Chrome" class="me-4" width="22" height="22"><span class="text-heading">Chrome on iPhone</span></td>
                <td class="text-truncate">iPhone 12x</td>
                <td class="text-truncate">Australia</td>
                <td class="text-truncate">13, July 2021 10:10</td>
              </tr>
              <tr>
                <td class="text-truncate"><img src="https://demos.themeselection.com/materio-bootstrap-html-laravel-admin-template/demo/assets/img/icons/brands/chrome.png" alt="Chrome" class="me-4" width="22" height="22"><span class="text-heading">Chrome on Android</span></td>
                <td class="text-truncate">Oneplus 9 Pro</td>
                <td class="text-truncate">Dubai</td>
                <td class="text-truncate">14, July 2021 15:15</td>
              </tr>
              <tr>
                <td class="text-truncate"><img src="https://demos.themeselection.com/materio-bootstrap-html-laravel-admin-template/demo/assets/img/icons/brands/chrome.png" alt="Chrome" class="me-4" width="22" height="22"><span class="text-heading">Chrome on MacOS</span></td>
                <td class="text-truncate">Apple iMac</td>
                <td class="text-truncate">India</td>
                <td class="text-truncate">16, July 2021 16:17</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        -->
      </div>




    </div>
    <!--/ User Content -->
  </div>


@endsection
