<x-tabbed-navigation :nav-items="[
          ['icon' => 'ri-group-line me-1_5', 'label' => 'Details', 'href' => route('admin.employee.show', $employee->employee_id)],
          ['icon' => 'ri-file-pdf-2-line me-1_5', 'label' => 'Documents', 'href' => route('admin.employee.showDocuments', $employee->employee_id)],
          ['icon' => 'ri-lock-2-line me-1_5', 'label' => 'Security', 'href' => route('admin.employee.showSecurity', $employee->employee_id)],
          ['icon' => 'ri-money-rupee-circle-line me-1_5', 'label' => 'Salary', 'href' => route('admin.employee.showSalary', $employee->employee_id)],
      ]" />
