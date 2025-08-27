<?php

namespace App\Services;

use App\Models\AdminConfigurations;

class AdminConfigService
{
  public function getAdminSettings()
  {
    return AdminConfigurations::all();
  }
  public function getCompanyName(int $index = 0): array
  {
    $json = AdminConfigurations::where('key', 'companies')->value('value');
    $companies = json_decode($json, true);

    // Safe check for valid structure
    if (is_array($companies) && isset($companies[$index]) && is_array($companies[$index])) {
      return [
        'company_name' => $companies[$index]['name'] ?? 'Unknown Company'
      ];
    }

    // Fallback if JSON is invalid or company not found
    return [
      'company_name' => 'Unknown Company'
    ];
  }



  public function getInvoiceConfig(int $companyId){
    $json = AdminConfigurations::where('key', 'companies')->value('value');

    if (!$json) {
      return [];
    }

    $companies = json_decode($json, true);

    return $companies[$companyId] ?? [];
  }
  public function getAllInvoiceConfigs()
  {
    $json = AdminConfigurations::where('key', 'companies')->value('value');

    if (!$json) {
      return [];
    }

    $companies = json_decode($json, true);

    return is_array($companies) ? $companies : [];
  }

  public function getSetting(string $key)
  {
    return AdminConfigurations::where('key', $key)->first();
  }

  public function updateSetting(string $key, $value)
  {
    $config = AdminConfigurations::where('key', $key)->first();

    if ($config) {
      $config->value = $value;
      $config->save();
    }
  }
}
