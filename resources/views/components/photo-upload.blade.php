<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card mb-6 p-4 text-center">
      <form method="POST" enctype="multipart/form-data" action="{{ $uploadRoute }}">
        @csrf

        <input type="hidden" name="avatar_preview" id="avatar_preview_{{ $inputName }}" value="{{ $existingFile }}">

        <!-- File Upload Section -->
        <div class="card-body">
          <div class="d-flex flex-column align-items-center gap-4">
            <!-- Image Preview -->
            <div class="position-relative profile-upload-container">
              <img src="{{ $existingFile ? asset('storage/' . $existingFile) : $defaultImage }}"
                   alt="{{ $label }}"
                   class="img-fluid rounded {{ $isAvatar ? 'profile-photo' : 'document-photo' }}"
                   id="uploadedAvatar_{{ $inputName }}" />
            </div>

            <!-- Upload Button -->
            <div class="button-wrapper text-center">
              <label for="upload_{{ $inputName }}" class="btn btn-lg btn-primary w-100" tabindex="0">
                <span class="d-block fs-5 fw-bold">Upload {{ $label }}</span>
                <i class="ri-upload-2-line d-block ms-2 fs-3"></i>
                <input type="file"
                       id="upload_{{ $inputName }}"
                       class="account-file-input"
                       name="{{ $inputName }}"
                       hidden
                       accept="image/png, image/jpeg, image/gif, application/pdf" />
              </label>

              <!-- Reset Button -->
              <button type="button" class="btn btn-outline-danger account-image-reset mt-3" data-target="{{ $inputName }}">
                <i class="ri-refresh-line d-block d-sm-none"></i>
                <span class="d-block fs-5">Reset</span>
              </button>

              <div class="mt-2">
                Allowed JPG, GIF, PNG, PDF. Max size of 2MB.
              </div>
              @error($inputName)
              <small class="text-danger fw-bold">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-success mt-4 w-100">Upload</button>
      </form>
    </div>
  </div>
</div>

<!-- Include JavaScript only in this component -->
@once
  @vite([
    'resources/assets/js/pages-edit-photo.js',
  ])
@endonce
