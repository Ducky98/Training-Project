<?php

namespace App\Services;

use App\Models\AdminConfigurations;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
  /**
   * Generate a unique invoice number
   *
   * @return string
   */
  public function generateInvoiceNo(): string
  {
    do {
      $date = now()->format('Ymd');
      $lastInvoiceId = (Invoice::max('id') ?? 0) + 1;

      // Keep only the last 4 digits
      $nextId = str_pad($lastInvoiceId % 10000, 4, '0', STR_PAD_LEFT);

      // Generate a 4-character random string
      $random = strtoupper(substr(md5(uniqid()), 0, 4));

      // Final Invoice Number
      $invoiceNo = "{$date}-{$nextId}-{$random}";
    } while (Invoice::where('invoiceId', $invoiceNo)->exists());

    return $invoiceNo;
  }

  /**
   * Create a new invoice
   *
   * @param array $invoiceData
   * @return Invoice
   */
  public function createInvoice(array $invoiceData): Invoice
  {
      return Invoice::create($invoiceData);
  }
  /**
   * Get company details from AdminConfigurations key-value table
   *
   * @return array
   */
  public function getCompanyDetails(int $companyId): array
  {
    $json = AdminConfigurations::where('key', 'companies')->value('value');

    $companies = json_decode($json, true);

    $company = $companies[$companyId] ?? [];

    return [
      'company_head' => $company['head'] ?? 'Default Head',
      'company_name' => $company['name'] ?? 'Default Company',
      'company_address' => $company['address'] ?? 'Default Address',
      'company_location' => $company['location'] ?? 'Default Location',
      'company_phone' => $company['phone'] ?? '000-000-0000',
      'company_email' => $company['email'] ?? 'info@example.com',
      'company_gst_number' => $company['gst_number'] ?? 'GST',
      'company_bank_name' => $company['bank_name'] ?? 'Default Bank',
      'company_bank_account_number' => $company['account_number'] ?? '0000000000',
      'company_ifsc_code' => $company['ifsc_code'] ?? 'DEFAULTIFSC',
      'cin_no' => $company['cin_no'] ?? 'DEFAULTCINNO',
    ];
  }



  /**
   * Add items to an invoice
   *
   * @param Invoice $invoice
   * @param array $items
   * @return void
   */
  public function addInvoiceItems(Invoice $invoice, array $items): int
  {
    if (empty($items)) {
      return 0;
    }

    InvoiceItem::insert($items);
    return count($items);
  }


}
