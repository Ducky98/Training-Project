{{-- resources/views/livewire/data-table.blade.php --}}
<div>
  {{-- Keep existing styles --}}
  <style>
    .sticky-top {
      position: sticky;
      top: 0;
      background-color: white;
      z-index: 1;
    }

    .sticky-start {
      position: sticky;
      left: 0;
      background-color: white;
      z-index: 1;
    }

    .sticky-end {
      position: sticky;
      right: 0;
      background-color: white;
      z-index: 1;
    }

    .sort-header {
      cursor: pointer;
      position: relative;
      padding-right: 1.5rem;
    }

    .sort-header:hover {
      background-color: rgba(67, 89, 113, 0.04);
    }

    .sort-icon {
      position: absolute;
      right: 0.5rem;
      top: 50%;
      transform: translateY(-50%);
      opacity: 0.3;
    }

    .sort-header:hover .sort-icon {
      opacity: 0.7;
    }

    .sort-header.active .sort-icon {
      opacity: 1;
    }

    .sort-header.active {
      background-color: rgba(67, 89, 113, 0.04);
    }

    /* Add new styles for empty state */
    .empty-state {
      padding: 3rem 1.5rem;
      text-align: center;
      background-color: #f8f9fa;
      border-radius: 0.375rem;
    }

    .empty-state-icon {
      font-size: 2.5rem;
      color: #d9dee3;
      margin-bottom: 1rem;
    }

    .empty-state-title {
      font-size: 1.25rem;
      font-weight: 500;
      color: #566a7f;
      margin-bottom: 0.5rem;
    }

    .empty-state-description {
      color: #697a8d;
      margin-bottom: 0;
    }
  </style>

  <div class="card">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-header">{{ $tableName ?? "Table" }}</h5>
      <div class="mx-5 d-flex gap-3 align-items-center">
        <div class="dropdown">
          <button class="btn btn-label-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ri-external-link-line me-sm-1"></i> Export
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
            <li><a class="dropdown-item" href="#" wire:click="export('csv')">CSV</a></li>
            <li><a class="dropdown-item" href="#" wire:click="export('excel')">Excel</a></li>
          </ul>
        </div>
        {!! $extraHeaderButtons !!}
      </div>
    </div>


      <div class="d-flex justify-content-between mx-5 mb-3">
        <div class="d-flex align-items-center gap-2">
          <label for="perPage">Show:</label>
          <select id="perPage" wire:model.live="perPage" class="form-select w-auto">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
          <label>entries</label>
        </div>

        @if($enableSearch)
          <div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." class="form-control">
          </div>
        @endif
      </div>
    @if(count($paginatedData) > 0)
      <div class="table-responsive text-nowrap px-4">
        <table class="table">
          <thead class="{{ $enableFixedHeader ? 'sticky-top' : '' }}">
          <tr>
            <!-- Add Index Column Header -->
            <th>#</th>

            <!-- Loop through columns for headers -->
            @foreach($columns as $column)
              <th wire:click="sort('{{ $column }}')"
                  class="sort-header {{ $sortField === $column ? 'active' : '' }}">
                {{ ucfirst($column) }}
                <span class="sort-icon">
                                @if($sortField === $column)
                    @if($sortDirection === 'asc')
                      <i class="ri-arrow-up-s-fill"></i>
                    @else
                      <i class="ri-arrow-down-s-fill"></i>
                    @endif
                  @else
                    <i class="ri-arrow-up-down-line"></i>
                  @endif
                            </span>
              </th>
            @endforeach

            <!-- Add Actions column if actions are provided -->
            @if(!empty($actions))
              <th>Actions</th>
            @endif
          </tr>
          </thead>
          <tbody class="table-border-bottom-0">
          @foreach($paginatedData as $row)
            <tr>
              <!-- Add Index Column Data -->
              <td class="{{ $enableFixedFirstColumn ? 'sticky-start' : '' }}">
                {{ ($paginatedData->currentPage() - 1) * $paginatedData->perPage() + $loop->iteration }}
              </td>

              <!-- Loop through columns for data -->
              @foreach($columns as $column)
                <td class="{{ $loop->last && $enableFixedLastColumn ? 'sticky-end' : '' }}">
                  @php
                    $value = $row[$column];
                    $isDate = false;
                    try {
                        \Carbon\Carbon::parse($value);
                        $isDate = true;
                    } catch (\Exception $e) {
                        $isDate = false;
                    }
                  @endphp

                  @if ($isDate)
                    {{ \Carbon\Carbon::parse($value)->format('d-m-Y') }}
                  @else
                    {{ $value }}
                  @endif
                </td>
              @endforeach

              <!-- Render action buttons for each row -->
              <td class="actions-cell">
                @foreach($actions as $action)
                  <a href="{{ route($action['route'], $row['id']) }}" class="{{ $action['class'] }}">
                    {{ $action['name'] }}
                  </a>
                @endforeach
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center mx-5 my-3 border-top border-2 pt-3">
        <div>
          Showing {{ $paginatedData->firstItem() ?? 0 }} to {{ $paginatedData->lastItem() ?? 0 }} of {{ $paginatedData->total() }} entries
        </div>
        <div>
          {{ $paginatedData->links() }}
        </div>
      </div>
    @else
      <!-- Empty State -->
      <div class="empty-state mx-5 my-4">
        <div class="empty-state-icon">
          <i class="ri-file-list-3-line"></i>
        </div>
        <h5 class="empty-state-title">No Clients Found</h5>
        <p class="empty-state-description">
          @if($search)
            No clients match your search criteria. Try adjusting your search.
          @else
            There are no clients in the system yet. Click the Create button to add one.
          @endif
        </p>
      </div>
    @endif
  </div>
</div>
