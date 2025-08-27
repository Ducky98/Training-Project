@extends('layouts/contentNavbarLayout')

@section('title', 'Employee List')

@section('vendor-style')
  {{-- DataTables CSS --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection

@section('content')
  <div class="card p-5">
    <div class="d-flex justify-content-between mb-3">
      <h4 class="mb-0">Employee List</h4>
      <a href="{{route('admin.employee.create')}}" class="btn btn-primary">Add Employee</a>
    </div>
    <table id="dataTable" class="dataTable">
      <thead>
      <tr>
        <th>No</th>
        <th>Employee ID</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>Status</th>
        <th>Joined On</th>
        <th>Action</th>
      </tr>
      </thead>
    </table>
  </div>

  @push('scripts')
    {{-- jQuery must be loaded before DataTables --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
      $(document).ready(function () {
        // Add CSRF token to AJAX requests
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $('#dataTable').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "{{ route('admin.employee.index') }}", // Make sure this route exists
            type: 'GET',
            error: function(xhr, error, thrown) {
              console.log('Ajax error:', error, thrown);
              console.log('Response:', xhr.responseText);
            }
          },
          columns: [
            { data: 'DT_RowIndex', name: 'id', orderable: true, searchable: false },
            { data: 'employee_id', name: 'employee_id' },
            { data: 'full_name', name: 'full_name', orderable: true, searchable: true },
            { data: 'mobile_number', name: 'mobile_number', orderable: false, searchable: true },
            { data: 'status', name: 'status', orderable: true, searchable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
          ],
          columnDefs: [
            { targets: [0], className: 'text-left' },
            { targets: [6], className: 'text-center' },
            { targets: [0, 1, 2, 3, 4, 5, 6], className: 'py-2' }
          ],
          fixedHeader: true,
          order: [[0, 'asc']]
        });
      });
    </script>
  @endpush
@endsection
