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
        <div class="card mb-4">
          <div class="card-header">
            <div class="border-bottom d-flex justify-content-between pb-4 mb-4">
              <h4 class="mb-0">Documents</h4>
            </div>
          </div>

          <div class="card-body">
            @foreach($requiredDocuments as $doc)
              @php
                $uploadedDoc = $uploadedDocuments[$doc] ?? null; // ✅ Correct

              @endphp

              <div class="p-2 border-bottom mb-3">
                <span class="h4">{{ ucfirst($doc) }}</span>

                <div class="d-flex justify-content-between align-items-center">
                  @if ($uploadedDoc)
                    <!-- Show uploaded document -->
                    @if (in_array(pathinfo($uploadedDoc['file_path'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                      <!-- Show image preview -->
                      <img src="{{ asset('storage/' . $uploadedDoc['file_path']) }}" alt="{{ $doc }}" class="img-thumbnail" width="150">
                    @elseif (pathinfo($uploadedDoc['file_path'], PATHINFO_EXTENSION) === 'pdf')
                      <!-- Show PDF icon -->
                      <i class="ri-file-pdf-line text-danger" style="font-size: 40px;"></i>
                    @else
                      <!-- Show generic file icon -->
                      <i class="ri-file-text-line text-secondary" style="font-size: 40px;"></i>
                    @endif
                    <div>
                      <a href="{{ asset('storage/' . $uploadedDoc['file_path']) }}" target="_blank" class="btn btn-sm btn-success">View</a>
                      <form action="{{ route('admin.employee.deleteDocument', $uploadedDoc['id']) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                      </form>
                    </div>
                  @else
                    <!-- Show upload button if missing -->
                    <div>
                      <span class="text-danger">Not Uploaded</span>
                      <a href="{{ route('admin.employee.uploadDocument', ['employee_id' => $employee->employee_id, 'document' => $doc]) }}"
                         class="btn btn-sm btn-primary">Upload</a>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach

            <!-- Show extra uploaded documents that are NOT in the config -->
            @foreach ($uploadedDocuments as $docType => $uploadedDoc)
              @if (!in_array($docType, $requiredDocuments))
                <div class="p-2 border-bottom mb-3">
                  <span class="h4">{{ ucfirst($docType) }} (Extra)</span>
                  <div class="d-flex justify-content-between align-items-center">
                    <img src="{{ asset('storage/' . $uploadedDoc['file_path']) }}" alt="{{ $docType }}" class="img-thumbnail" width="150">
                    <div>
                      <a href="{{ asset('storage/' . $uploadedDoc['file_path']) }}" target="_blank" class="btn btn-sm btn-success">View</a>
                      <form action="{{ route('admin.employee.deleteDocument', $uploadedDoc['id']) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                      </form>
                    </div>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      </div>




    </div>
    <!--/ User Content -->
  </div>


@endsection
