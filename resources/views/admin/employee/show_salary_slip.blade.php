@extends('layouts/contentNavbarLayout')

@section('title', 'Employee #'.$employee->employee_id)

@section('vendor-style')
  <script>
    // Define variables in Blade and pass them to JavaScript
    const deleteEmployeeUrl = "{{ route('admin.employee.delete', ['employee_id' => $employee->employee_id]) }}";
    const employeeIndexUrl = "{{ route('admin.employee.index') }}";
    const csrfToken = "{{ csrf_token() }}";
  </script>

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
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Salary Summary</h5>
            <a href="{{ route('admin.employee.salary.create', $employee->employee_id) }}" class="btn btn-primary">
              <i class="ri-edit-fill"></i> Add
            </a>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- Total Salary To Be Given (Attendance) -->
              <div class="col-md-4">
                <h6 class="mb-1">Total Salary Due (Attendance)</h6>
                <p class="fs-4 fw-bold text-warning">
                  {{ number_format($totalDueSalary, 2) }} <small class="text-muted">INR</small>
                </p>
              </div>

              <!-- Total Salary Already Paid -->
              <div class="col-md-4">
                <h6 class="mb-1">Total Salary Paid</h6>
                <p class="fs-4 fw-bold text-success">
                  {{ number_format($paidSalary, 2) }} <small class="text-muted">INR</small>
                </p>
              </div>

              <!-- Last Attendance or Salary Record -->
              <div class="col-md-4">
                <h6 class="mb-1">Last Attendance / Last Salary Paid</h6>
                @if ($lastAttendance)
                  <p class="fs-6 mb-0">
                    Attendance: {{ number_format($lastAttendance->daily_rate, 2) }} INR on {{ \Carbon\Carbon::parse($lastAttendance->date)->format('d M Y') }}
                  </p>
                @else
                  <p class="fs-6 mb-0 text-muted">No attendance records yet.</p>
                @endif

                @if ($lastSalary)
                  <p class="fs-6 mb-0">
                    Paid: {{ number_format($lastSalary->net_pay, 2) }} INR on {{ \Carbon\Carbon::parse($lastSalary->payment_date)->format('d M Y') }}
                  </p>
                @else
                  <p class="fs-6 mb-0 text-muted">No salary records yet.</p>
                @endif
              </div>
            </div>
          </div>
        </div>



        <div class="card mb-6">
          <h5 class="card-header">Salary Records</h5>
          <div class="card-body">
            <table id="dataTable" class="display">
              <thead>
              <tr>
                <th>No</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Note</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>

              @foreach($salaries as $index => $salary)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $salary->net_pay }}</td>
                  <td>{{ \Carbon\Carbon::parse($salary->payment_date)->format('d-M-Y') }}</td>
                  <td>{{ ucwords($salary->mode_of_payment) }}</td>
                  <td>{{ $salary->note }}</td>
                  <td>
                    <a href="#" class="btn btn-sm btn-danger delete-salary" data-id="{{ $salary->id }}">Delete</a>
                    <a href="{{route('admin.employee.salary.print', $salary->id)}}" target="_blank" class="btn btn-sm btn-info">Print</a>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
    <!--/ User Content -->
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteSalaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this salary record? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script type="module">
      $(document).ready(function () {
        // Initialize DataTable and store the reference
        const dataTable = $('#dataTable').DataTable();
        const deleteSalaryUrl = "{{ route('admin.employee.salary.destroy', ['salary_id' => '__ID__']) }}";
        // Handle delete button click
        let salaryIdToDelete = null;

        $(document).on('click', '.delete-salary', function(e) {
          e.preventDefault(); // Prevent default action if it's an <a> tag
          salaryIdToDelete = $(this).data('id');
          $('#deleteSalaryModal').modal('show');
        });

        // Confirm delete action
        $('#confirmDelete').on('click', function() {
          if (salaryIdToDelete) {
            // Make the DELETE request
            $.ajax({
              url: deleteSalaryUrl.replace('__ID__', salaryIdToDelete),
              type: 'DELETE',
              data: {
                _token: csrfToken
              },
              success: function(response) {
                $('#deleteSalaryModal').modal('hide');


                // Reload the page to reflect the changes
                location.reload();
              },
              error: function(xhr) {
                console.error(xhr);

                // Display error message
                if (typeof toastr !== 'undefined') {
                  toastr.error('An error occurred while deleting the salary record');
                } else {
                  alert('An error occurred while deleting the salary record');
                }
              }
            });
          }
        });
      });
    </script>
  @endpush
@endsection
