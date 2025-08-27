<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DataTable extends Component
{
  use WithPagination;

  public $data;
  public $tableName;
  public $extraHeaderButtons;
  public $columns;
  public $search = '';
  public $actions = [];
  public $enableSearch = true;
  public $enableFixedHeader = true;
  public $enableFixedFirstColumn = false;
  public $enableFixedLastColumn = false;
  public $perPage = 10;
  public $sortField = '';
  public $sortDirection = 'asc';

  protected $queryString = [
    'search' => ['except' => ''],
    'perPage' => ['except' => 10],
    'sortField' => ['except' => ''],
    'sortDirection' => ['except' => 'asc'],
  ];

  public function mount($data, $columns, $tableName = null, $extraHeaderButtons = null,$actions = [])
  {
    $this->data = $data;
    $this->columns = $columns;
    $this->tableName = $tableName;
    $this->extraHeaderButtons = $extraHeaderButtons;
    $this->actions = $actions;
  }

  public function sort($field)
  {
    if ($this->sortField === $field) {
      $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
      $this->sortField = $field;
      $this->sortDirection = 'asc';
    }

    $this->resetPage();
  }

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function updatingPerPage()
  {
    $this->resetPage();
  }

  protected function getFilteredData(): Collection
  {
    return collect($this->data)
      ->filter(function ($item) {
        if (empty($this->search)) {
          return true;
        }

        foreach ($this->columns as $column) {
          $value = data_get($item, $column, '');
          if (str_contains(strtolower((string)$value), strtolower($this->search))) {
            return true;
          }
        }
        return false;
      })
      ->when($this->sortField, function ($collection) {
        return $collection->sortBy($this->sortField, SORT_REGULAR, $this->sortDirection === 'desc');
      });
  }

  protected function paginateData(Collection $filteredData): LengthAwarePaginator
  {
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $perPage = $this->perPage;

    $currentItems = $filteredData->slice(($currentPage - 1) * $perPage, $perPage)->values();

    return new LengthAwarePaginator(
      $currentItems,
      $filteredData->count(),
      $perPage,
      $currentPage,
      ['path' => LengthAwarePaginator::resolveCurrentPath()]
    );
  }

  public function render()
  {
    $filteredData = $this->getFilteredData();
    $paginatedData = $this->paginateData($filteredData);

    return view('livewire.data-table', [
      'paginatedData' => $paginatedData,
    ]);
  }


  public function export($type)
  {
    return match ($type) {
      'csv' => $this->exportToCsv(),
      'excel' => $this->exportToExcel(),
      default => null,
    };
  }
  public function paginationView()
  {
    return 'vendor.livewire.custom-pagination';
  }
  private function exportToCsv()
  {
    // Implement CSV export logic here
    $filteredData = $this->getFilteredData();
    // Add your CSV export implementation
  }

  private function exportToExcel()
  {
    // Implement Excel export logic here
    $filteredData = $this->getFilteredData();
    // Add your Excel export implementation
  }
}
