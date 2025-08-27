<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
  protected $fillable = ['invoice_id', 'name', 'cg_name','supervisor','shift','code', 'cg_id', 'cost', 'days', 'total'];

}
