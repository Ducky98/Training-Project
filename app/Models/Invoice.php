<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  protected $fillable = [
    'invoiceId', 'invoiceDate', 'company_details', 'billing_details',
    'date_range', 'tax_rate', 'discount', 'include_gst'
  ];

  protected $casts = [
    'company_details' => 'array',
    'billing_details' => 'array',
    'invoiceDate' => 'datetime'

  ];

  public function items()
  {
    return $this->hasMany(InvoiceItem::class);
  }
}
