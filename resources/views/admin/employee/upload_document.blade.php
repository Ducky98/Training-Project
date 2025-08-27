@extends('layouts/contentNavbarLayout')

@section('title', 'Upload Document')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xl-12">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Upload Document - {{ $employee->full_name }}</h5>
            <a href="{{ route('admin.employee.showDocuments', $employee->employee_id) }}" class="btn btn-secondary btn-sm">
              <i class="bx bx-arrow-back"></i> Back
            </a>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.employee.storeUploadDocument', ['employee_id' => $employee->id, 'document' => $document]) }}"
                  method="POST"
                  enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="document_type" value="{{ $document }}">

              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="document_file" class="form-label">Document File</label>
                    <input type="file"
                           class="form-control @error('document_file') is-invalid @enderror"
                           id="document_file"
                           name="document_file"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    @error('document_file')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card h-100">
                    <div class="card-body">
                      <h6 class="card-title">Document Information</h6>
                      <dl class="row mt-3">
                        <dt class="col-sm-4">Employee ID:</dt>
                        <dd class="col-sm-8">{{ $employee->employee_id }}</dd>

                        <dt class="col-sm-4">Document Type:</dt>
                        <dd class="col-sm-8">{{ ucfirst($document) }}</dd>

                        <dt class="col-sm-4">Allowed Types:</dt>
                        <dd class="col-sm-8">PDF, DOC, DOCX, JPG, JPEG, PNG</dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary">
                    <i class="bx bx-upload me-1"></i> Upload Document
                  </button>
                  <button type="reset" class="btn btn-outline-secondary">
                    <i class="bx bx-reset me-1"></i> Reset
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Preview file size before upload
      document.getElementById('document_file').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
          if (fileSize > 10) { // Assuming 10MB limit
            alert('File size exceeds 10MB limit. Please choose a smaller file.');
            this.value = '';
          }
        }
      });
    });
  </script>
@endsection
