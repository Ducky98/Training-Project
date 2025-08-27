<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use Carbon\Carbon;

class Dashboard extends Controller
{
  public function index()
  {
    $totalClients = Client::count();

    $clientsThisMonth = Client::whereMonth('created_at', now()->month)
      ->whereYear('created_at', now()->year)
      ->count();

    $totalEmployees = Employee::count();

    return view('admin.dashboard.dashboard', compact('totalClients', 'clientsThisMonth', 'totalEmployees'));
  }

}
