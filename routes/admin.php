<?php

use App\Http\Controllers\Admin\AdminConfiguration;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->group(function () {
  //  Dashboard
  Route::get('/', [Dashboard::class, 'index'])->name('dashboard');

  // Client
  Route::get('/client/', [ClientController::class, 'index'])->name('admin.client.index');
  Route::get('/client/new', [ClientController::class, 'create'])->name('admin.client.create');
  Route::get('/client/{id}', [ClientController::class, 'show'])->name('admin.client.show');
  Route::get('/client/{id}/edit', [ClientController::class, 'edit'])->name('admin.client.edit');
  Route::put('/client/{id}/update', [ClientController::class, 'update'])->name('admin.client.update');
  Route::delete('/client/{id}/delete', [ClientController::class, 'delete'])->name('admin.client.delete');
  Route::post('/client/new', [ClientController::class, 'store'])->name('admin.client.store');



  // Patient
  Route::get('/patient/new/{client_id}', [ClientController::class, 'create'])->name('admin.patient.create');

  //Employee
  Route::get('/employee/all', [EmployeeController::class, 'index'])->name('admin.employee.index');
  Route::get('/employee/new', [EmployeeController::class, 'create'])->name('admin.employee.create');
  Route::post('/employee/add', [EmployeeController::class, 'store'])->name('admin.employee.store');
  Route::get('/employee/{employee_id}', [EmployeeController::class, 'show'])->name('admin.employee.show');
  Route::get('/employee/{employee_id}/document', [EmployeeController::class, 'showDocuments'])->name('admin.employee.showDocuments');
  Route::get('/employee/{employee_id}/security', [EmployeeController::class, 'showSecurity'])->name('admin.employee.showSecurity');
  //Employee - Salary
  Route::get('/employee/{employee_id}/salary', [EmployeeController::class, 'showSalary'])->name('admin.employee.showSalary');
  Route::get('/employee/{employee_id}/salary/add', [EmployeeController::class, 'createSalary'])->name('admin.employee.salary.create');
  Route::post('/employee/{employee_id}/salary/store', [EmployeeController::class, 'storeSalary'])->name('admin.employee.salary.store');
  Route::delete('/salary/{salary_id}', [EmployeeController::class, 'destroySalary'])->name('admin.employee.salary.destroy');
  Route::get('/admin/employee/{employee_id}/salary/data', [EmployeeController::class, 'getSalaryData'])->name('admin.employee.salary.data');
  Route::get('/admin//salary/{salary_id}/print', [EmployeeController::class, 'printSalarySlip'])->name('admin.employee.salary.print');

  Route::get('/employee/{employee_id}/edit/info', [EmployeeController::class, 'edit'])->name('admin.employee.edit');
  Route::get('/employee/{employee_id}/edit/bank', [EmployeeController::class, 'editBankDetail'])->name('admin.employee.edit.bank');

  Route::put('/employee/{employee_id}/update/info', [EmployeeController::class, 'update'])->name('admin.employee.update');

  Route::put('/employee/{employee_id}/update/bank', [EmployeeController::class, 'updateBank'])->name('admin.employee.update.bank');
  Route::get('/employee/{employee_id}/edit/address', [EmployeeController::class, 'editAddress'])->name('admin.employee.editAddress');
  Route::put('/employee/{employee_id}/update/address', [EmployeeController::class, 'updateAddress'])->name('admin.employee.updateAddress');
  Route::get('/employee/{employee_id}/edit/profile-photo', [EmployeeController::class, 'editProfilePhoto'])
    ->name('admin.employee.edit_profile_photo');
  Route::post('/employee/{id}/update/profile-photo', [EmployeeController::class, 'updateProfilePhoto'])
    ->name('admin.employee.update_profile_photo');
  Route::get('/employee/{employee_id}/edit/upload/{document}', [EmployeeController::class, 'uploadDocument'])
    ->name('admin.employee.uploadDocument');
  Route::post('/employee/{employee_id}/edit/upload/{document}', [EmployeeController::class, 'storeUploadDocument'])
    ->name('admin.employee.storeUploadDocument');
  Route::get('/employee/{employee_id}/edit/upload/{document}/edit', [EmployeeController::class, 'editDocument'])
    ->name('admin.employee.editDocument');
  Route::delete('/employee/document/{id}/delete', [EmployeeController::class, 'deleteDocument'])
    ->name('admin.employee.deleteDocument');
  Route::delete('/employee/{employee_id}/delete', [EmployeeController::class, 'delete'])->name('admin.employee.delete');

  //Invoice
  Route::get('/invoice/', [InvoiceController::class, 'index'])->name('admin.invoice.index');
  Route::get('/invoice/new/{id?}', [InvoiceController::class, 'create'])
    ->where('id', '[0-9]+')
    ->name('admin.invoice.create');
  Route::get('/invoice/{id}', [InvoiceController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('admin.invoice.show');
  Route::get('/invoice/print/{id}', [InvoiceController::class, 'print'])
    ->where('id', '[0-9]+')
    ->name('admin.invoice.print');
  Route::post('/invoice/store', [InvoiceController::class, 'store'])
    ->name('admin.invoice.store');
  Route::delete('/invoice/{invoice}/delete', [InvoiceController::class, 'delete'])
    ->name('admin.invoice.delete');




  Route::get('/config/admin',[AdminConfiguration::class,'adminConfig'])->name('admin.config.adminConfig');
  Route::get('/config/employee',[AdminConfiguration::class,'employeeConfig'])->name('admin.config.employeeConfig');

    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
    Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');
    Route::delete('/attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
    Route::post('/attendances/bulk-delete', [AttendanceController::class, 'bulkDelete'])->name('attendances.mass-delete');

    Route::post('/mark-attendance', [AttendanceController::class, 'markAttendance'])->name('mark.attendance');
});
Route::get('/employee/{employee_id}/idCard', [EmployeeController::class, 'printIdCard'])->name('admin.employee.idCard');
