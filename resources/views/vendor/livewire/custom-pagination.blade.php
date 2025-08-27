{{-- resources/views/vendor/livewire/custom-pagination.blade.php --}}
<div>
  <style>
    .pagination-wrapper {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin: 1rem 0;
    }

    .pagination {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
      gap: 0.25rem;
    }

    .page-item {
      margin: 0;
    }

    .page-item.disabled .page-link {
      background-color: #eeeeef;
      color: #6c757d;
      cursor: not-allowed;
      opacity: 0.65;
      transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .page-item.active .page-link {
      border-color: #8c57ff;
      background-color: #8c57ff;
      color: #fff;
      transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;

    }

    .page-link {
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 2.25rem;
      height: 2.25rem;
      padding: 0.375rem 0.75rem;
      margin-left: -1px;
      font-size: 0.9375rem;
      line-height: 1.5;
      color: #435971;
      background-color: #eeeeef;
      border: 1px solid #d9dee3;
      border-radius: 0.375rem;
      text-decoration: none;
      transition: all 0.2s ease-in-out;
    }
    .page-item .page-link.nav-btn{
      border-radius: .4rem;
    }
    .page-link:hover {
      background-color: #e9ecef;
      border-color: #dee2e6;
      color: #2f3d4d;
      text-decoration: none;
    }

    .page-link:focus {
      outline: none;
      /*box-shadow: 0 0 0 0.2rem rgba(67, 89, 113, 0.25);*/
    }

    /* Ellipsis styling */
    .page-item.disabled span.page-link {
      background-color: rgba(140, 87, 255, 0.2);
      color: #8c57ff;
      border: none;
    }

    /* Previous/Next buttons */
    .pagination-prev-nav,
    .pagination-next-nav {
      font-weight: 500;
    }

    /* Mobile responsiveness */
    @media (max-width: 576px) {
      .pagination {
        gap: 0.125rem;
      }

      .page-link {
        min-width: 2rem;
        height: 2rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
      }

      /* Hide some page numbers on mobile */
      .pagination > li:not(.pagination-prev-nav):not(.pagination-next-nav):not(.active):not(.disabled) {
        display: none;
      }
    }
  </style>

  @if ($paginator->hasPages())
    <nav>
      <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
          <li class="page-item disabled" aria-disabled="true">
            <span class="page-link nav-btn">Previous</span>
          </li>
        @else
          <li class="page-item ">
            <button type="button" class="page-link nav-btn" wire:click="previousPage" wire:loading.attr="disabled" rel="prev">
              Previous
            </button>
          </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
          {{-- "Three Dots" Separator --}}
          @if (is_string($element))
            <li class="page-item disabled" aria-disabled="true">
              <span class="page-link">{{ $element }}</span>
            </li>
          @endif

          {{-- Array Of Links --}}
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <li class="page-item active" aria-current="page">
                  <span class="page-link">{{ $page }}</span>
                </li>
              @else
                <li class="page-item">
                  <button type="button" class="page-link" wire:click="gotoPage({{ $page }})">
                    {{ $page }}
                  </button>
                </li>
              @endif
            @endforeach
          @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
          <li class="page-item">
            <button type="button" class="page-link nav-btn" wire:click="nextPage" wire:loading.attr="disabled" rel="next">
              Next
            </button>
          </li>
        @else
          <li class="page-item disabled" aria-disabled="true">
            <span class="page-link nav-btn">Next</span>
          </li>
        @endif
      </ul>
    </nav>
  @endif
</div>
