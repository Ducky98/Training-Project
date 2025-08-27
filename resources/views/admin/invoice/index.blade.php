@extends('layouts/contentNavbarLayout')

@section('title', 'Invoice List')

@section('vendor-style')
  <script src="{{ asset('js/datatable.js') }}"></script>
@endsection

@section('content')
  <div class="card p-5">
    <h4>Invoice List</h4>
    <table id="invoiceTable" class="dataTable">
      <thead>
      <tr>
        <th>S No.</th>
        <th>Invoice Number</th>
        <th>Client Name</th>
        <th>Invoice Date</th>
        <th>Action</th>
      </tr>
      </thead>
    </table>
  </div>

  @push('scripts')
    <script type="module">
      $(document).ready(function () {
        $('#invoiceTable').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: window.location.href,
            type: 'GET',
            error: function (xhr, error, thrown) {
              console.error('DataTable Error:', error);
              alert('Error loading invoice data. Please try again.');
            }
          },
          columns: [
            {
              data: 'DT_RowIndex',
              name: 'DT_RowIndex',
              orderable: false,
              searchable: false
            },
            {
              data: 'invoiceId',
              name: 'invoiceId'
            },
            {
              data: 'client_name',
              name: 'client_name'
            },
            {
              data: 'invoiceDate',
              name: 'invoiceDate'
            },
            {
              data: 'action',
              name: 'action',
              orderable: false,
              searchable: false
            }
          ],
          columnDefs: [
            { targets: [0], className: 'text-left' },
            { targets: [1, 2], className: 'py-2' }
          ],
          fixedHeader: true,
          order: [[1, 'desc']] // Order by invoiceId by default
        });
      });
    </script>
  @endpush
@endsection
