<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminConfiguration extends Controller
{
    public function adminConfig(): View
    {
      return view('admin.config.admin-config');
    }

  public function employeeConfig(): View
  {
    return view('admin.config.employee-config');
  }
}
