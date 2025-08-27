<?php

namespace App\Livewire\Admin;

use App\Services\EmployeeService;
use Livewire\Component;

class EmployeeConfig extends Component
{
  public array $documents = [];
  public bool $isEditing = false;
  public ?string $newDocument = '';
  private EmployeeService $employeeService;

  /**
   * Inject EmployeeService and initialize data.
   */
  public function boot(EmployeeService $employeeService)
  {
    $this->employeeService = $employeeService;
  }

  public function mount()
  {
    $this->documents = $this->employeeService->getEmployeeDocuments();
  }

  /**
   * Enable Edit Mode
   */
  public function edit()
  {
    $this->isEditing = true;
  }

  /**
   * Cancel Edit Mode
   */
  public function cancelEdit()
  {
    $this->isEditing = false;
    $this->documents = $this->employeeService->getEmployeeDocuments();
    $this->newDocument = '';
  }

  /**
   * Add new document to the list
   */
  public function addDocument()
  {
    if (!empty($this->newDocument) && !in_array($this->newDocument, $this->documents)) {
      $this->documents[] = trim($this->newDocument);
      $this->newDocument = '';
    }
  }

  /**
   * Remove document from the list
   */
  public function removeDocument($index)
  {
    unset($this->documents[$index]);
    $this->documents = array_values($this->documents);
  }

  /**
   * Save Updated Employee Document Config
   */
  public function save()
  {
    $this->employeeService->updateEmployeeDocuments($this->documents);
    session()->flash('success', 'Employee document configuration updated.');
    $this->isEditing = false;
  }

  public function render()
  {
    return view('livewire.admin.employee-config');
  }
}
