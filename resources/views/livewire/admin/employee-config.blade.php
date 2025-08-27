<div class="card mb-6">
  <div class="card-header d-flex align-items-center justify-content-between">
    <h5 class="mb-0">Employee Document Configuration</h5>
    <div class="text-muted float-end">
      @if ($isEditing)
        <button type="button" class="btn btn-secondary me-2" wire:click="cancelEdit">
          <i class="ri-close-line ri-20px me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary" wire:click="save">
          <i class="ri-save-line ri-20px me-1"></i> Save Changes
        </button>
      @else
        <button wire:click="edit" class="btn btn-primary">
          <i class="ri-edit-2-line ri-20px me-1"></i> Edit Configuration
        </button>
      @endif
    </div>
  </div>

  <div class="card-body">
    @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if ($isEditing)
      <form wire:submit.prevent="save" class="mb-4">
        <div class="mb-4">
          <label class="form-label fw-semibold">Required Documents</label>
          <div class="document-list mb-3">
            @foreach ($documents as $index => $document)
              <div class="d-flex align-items-center mb-2">
                <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                <span class="flex-grow-1">{{ $document }}</span>
                <button type="button" class="btn btn-link text-danger p-0"
                        wire:click="removeDocument({{ $index }})">
                  <i class="ri-delete-bin-line ri-20px"></i>
                </button>
              </div>
            @endforeach
          </div>

          <div class="input-group">
            <input type="text" class="form-control"
                   wire:model="newDocument"
                   wire:keydown.enter.prevent="addDocument"
                   placeholder="Enter new document name">
            <button class="btn btn-outline-primary" type="button" wire:click="addDocument">
              <i class="ri-add-line ri-20px me-1"></i> Add
            </button>
          </div>
          <small class="text-muted">Press Enter or click Add to insert a new document</small>
        </div>
      </form>
    @else
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th width="80">#</th>
            <th>Required Document</th>
          </tr>
          </thead>
          <tbody>
          @forelse ($documents as $index => $document)
            <tr>
              <td class="text-center">{{ $index + 1 }}</td>
              <td class="text-capitalize">{{ $document }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="2" class="text-center text-muted">
                No documents configured
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

@push('scripts')
  <script>
    document.addEventListener('keydown', function(e) {
      if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
      @this.call('save');
      }
    });
  </script>
@endpush
