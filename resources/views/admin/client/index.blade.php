@extends('layouts/contentNavbarLayout')

@section('title', 'Client List')
@section('vendor-style')
  <script src="{{ asset('js/datatable.js') }}"></script>
@endsection

@section('page-script')

@endsection
@section('content')
  <div class="card p-5">
    <div class="d-flex justify-content-between mb-3">
      <h4 class="mb-0">Client List</h4>
      <a href="{{route('admin.client.create')}}" class="btn btn-primary">Add Client</a>
    </div>
    <table id="dataTable" class="dataTable">
      <thead>
      <tr>
        <th>No</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>Action</th>
      </tr>
      </thead>
    </table>
  </div>
  @push('scripts')
    <script type="module">
      $(document).ready(function () {
        $('#dataTable').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: window.location.href,
            type: 'GET'
          },
          columns: [
            {
              data: 'DT_RowIndex',
              name: 'id',
              orderable: true,
              searchable: false
            },
            { data: 'name', name: 'name', orderable: true, searchable: true },
            { data: 'mobile_number', name: 'mobile_number', orderable: false, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
          ],
          columnDefs: [
            { targets: [0], className: 'text-left' },
            { targets: [3], className: 'text-center' },
            { targets: [0, 1, 2, 3], className: 'py-2' }
          ],
          fixedHeader: true,  // Enables sticky header
          order: [[0, 'asc']] // Default order by first column ascending
        });
      });
    </script>
  @endpush
@endsection
