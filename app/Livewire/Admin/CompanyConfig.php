<?php

namespace App\Livewire\Admin;

use App\Models\AdminConfigurations;
use Livewire\Component;

class CompanyConfig extends Component
{
  public $companies = [];
  public $selectedCompanyIndex = 0;
  public $isEditing = false;

  public $company_name, $company_address, $company_location,
    $company_bank_name, $company_bank_account_number, $company_ifsc_code,
    $company_gst_number, $company_email, $company_phone, $cin_no;

  public function mount()
  {
    $json = AdminConfigurations::where('key', 'companies')->value('value');
    $this->companies = json_decode($json, true) ?? [];

    // If no companies exist, create a default one
    if (empty($this->companies)) {
      $this->companies = [
        [
          'name' => 'Default Company',
          'address' => '',
          'location' => '',
          'bank_name' => '',
          'account_number' => '',
          'ifsc_code' => '',
          'gst_number' => '',
          'email' => '',
          'phone' => '',
          'cin_no' => '',
        ]
      ];
    }

    $this->loadCompanyData();
  }

  public function loadCompanyData()
  {
    // Ensure selectedCompanyIndex is within bounds
    if ($this->selectedCompanyIndex >= count($this->companies)) {
      $this->selectedCompanyIndex = 0;
    }

    $company = $this->companies[$this->selectedCompanyIndex] ?? [];

    $this->company_name = $company['name'] ?? '';
    $this->company_address = $company['address'] ?? '';
    $this->company_location = $company['location'] ?? '';
    $this->company_bank_name = $company['bank_name'] ?? '';
    $this->company_bank_account_number = $company['account_number'] ?? '';
    $this->company_ifsc_code = $company['ifsc_code'] ?? '';
    $this->company_gst_number = $company['gst_number'] ?? '';
    $this->company_email = $company['email'] ?? '';
    $this->company_phone = $company['phone'] ?? '';
    $this->cin_no = $company['cin_no'] ?? '';
  }

  // This method is automatically called when selectedCompanyIndex changes
  public function updatedSelectedCompanyIndex()
  {
    $this->loadCompanyData();
    $this->isEditing = false; // Exit edit mode when switching companies

    // Optional: Add a small delay to show loading state
    $this->dispatch('company-changed');
  }

  public function edit()
  {
    $this->isEditing = true;
  }

  public function cancelEdit()
  {
    $this->isEditing = false;
    $this->loadCompanyData(); // Reload original data
  }

  public function save()
  {
    // Basic validation
    $this->validate([
      'company_name' => 'required|string|max:255',
      'company_email' => 'nullable|email|max:255',
      'company_phone' => 'nullable|string|max:20',
    ]);

    // Update the selected company in the array
    $this->companies[$this->selectedCompanyIndex] = [
      'name' => trim($this->company_name),
      'address' => trim($this->company_address),
      'location' => trim($this->company_location),
      'bank_name' => trim($this->company_bank_name),
      'account_number' => trim($this->company_bank_account_number),
      'ifsc_code' => trim($this->company_ifsc_code),
      'gst_number' => trim($this->company_gst_number),
      'email' => trim($this->company_email),
      'phone' => trim($this->company_phone),
      'cin_no' => trim($this->cin_no),
    ];

    // Save to database
    AdminConfigurations::updateOrCreate(
      ['key' => 'companies'],
      ['value' => json_encode($this->companies)]
    );

    $this->isEditing = false;

    // Optional: Show success message
    session()->flash('message', 'Company configuration updated successfully!');
    $this->dispatch('company-saved');
  }

  public function addNewCompany()
  {
    $newCompany = [
      'name' => 'New Company ' . (count($this->companies) + 1),
      'address' => '',
      'location' => '',
      'bank_name' => '',
      'account_number' => '',
      'ifsc_code' => '',
      'gst_number' => '',
      'email' => '',
      'phone' => '',
      'cin_no' => '',
    ];

    $this->companies[] = $newCompany;
    $this->selectedCompanyIndex = count($this->companies) - 1;
    $this->loadCompanyData();
    $this->isEditing = true; // Automatically edit the new company
  }

  public function deleteCompany()
  {
    if (count($this->companies) > 1) {
      unset($this->companies[$this->selectedCompanyIndex]);
      $this->companies = array_values($this->companies); // Re-index array

      // Adjust selected index if necessary
      if ($this->selectedCompanyIndex >= count($this->companies)) {
        $this->selectedCompanyIndex = count($this->companies) - 1;
      }

      $this->loadCompanyData();

      // Save to database
      AdminConfigurations::updateOrCreate(
        ['key' => 'companies'],
        ['value' => json_encode($this->companies)]
      );

      session()->flash('message', 'Company deleted successfully!');
    } else {
      session()->flash('error', 'Cannot delete the last company!');
    }
  }

  public function render()
  {
    return view('livewire.admin.company-config');
  }
}
