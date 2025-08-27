@extends('layouts/contentNavbarLayout')

@section('title', 'Add Salary for ' . $employee->first_name . ' ' . $employee->last_name)

@push('style')
  <style>
    .disabled-input {
      color: #919097;
      background-color: #f2f2f3;
      border-color: #cdcbd0;
      opacity: 1;
      pointer-events: none;
    }

    .disabled-input:focus {
      color: #aba8b1;
      background-color: #f2f2f3;
      border-color: #cdcbd0;
      opacity: 1;
      outline: none;
    }
  </style>
@endpush

@section('content')
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card mx-lg-3 border-1 border-dark rounded-0">
    <form action="{{ route('admin.employee.salary.store', $employee->employee_id) }}" method="POST" enctype="multipart/form-data">
      @csrf
    <div class="card-header">
      <div class="d-flex justify-content-center mx-lg-12" style="background: #589dda; border-radius: 0 0 110px 110px;">
        <select name="company_name" id="company-select" class="form-select w-auto text-gray w-60 text-center fs-3">
          @foreach ($company_name as $company)
            <option value="{{ $company['name'] ?? 'Unnamed Company' }}">
              {{ $company['name'] ?? 'Unnamed Company' }}
            </option>
          @endforeach
        </select>

      </div>


      <div class="d-flex justify-content-center align-items-center mt-3">
        <p class="mb-0 me-2">Salary Slip From</p>
        <select name="salary_month_from" id="salary_month_from" class="form-select mx-2" style="width: auto; padding: 2px 8px;">
          @for ($m = 1; $m <= 12; $m++)
            @php $monthName = date('F', mktime(0, 0, 0, $m, 1)); @endphp
            <option value="{{ $monthName }}" {{ $m == date('n')-1 ? 'selected' : '' }}>
              {{ $monthName }}
            </option>
          @endfor
        </select>

        <b class="me-2">-</b>
        <select name="salary_year_from" id="salary_year_from" class="form-select" style="width: 90px; padding: 2px 8px;">
          @for ($y = date('Y'); $y >= 2000; $y--)
            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
              {{ $y }}
            </option>
          @endfor
        </select>

        <p class="mb-0 ms-3 me-2">To</p>

        <select name="salary_month_to" id="salary_month_to" class="form-select mx-2" style="width: auto; padding: 2px 8px;">
          @for ($m = 1; $m <= 12; $m++)
            @php $monthName = date('F', mktime(0, 0, 0, $m, 1)); @endphp
            <option value="{{ $monthName }}" {{ $m == date('n')-1 ? 'selected' : '' }}>
              {{ $monthName }}
            </option>
          @endfor
        </select>

        <b class="me-2">-</b>
        <select name="salary_year_to" id="salary_year_to" class="form-select" style="width: 90px; padding: 2px 8px;">
          @for ($y = date('Y'); $y >= 2000; $y--)
            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
              {{ $y }}
            </option>
          @endfor
        </select>
      </div>

    </div>


      <div class="card-body border-top border-bottom border-1 border-dark py-0">
        <div class="row">
          <div class="col-md-6 pt-2">
            <div class="mb-3">
              <label for="employee_id" class="form-label">Employee ID</label>
              <input type="text" name="employee_id" id="employee_id" class="form-control disabled-input" value="{{ $employee->employee_id }}" readonly required>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Employee Name</label>
              <input type="text" name="name" id="name" class="form-control disabled-input" value="{{ $employee->first_name . ' ' . $employee->last_name ?? '' }}" readonly required>
            </div>
            <div class="mb-3">
              <label for="designation" class="form-label">Designation</label>
              <input type="text" name="designation" id="designation" class="form-control {{ $employee->designation ? 'disabled-input' : '' }}" value="{{ $employee->designation ?? '' }}" {{ $employee->designation ? 'readonly' : '' }} placeholder="Enter Designation (Will be Saved for next time)" required>
            </div>
            <div class="mb-3">
              <label for="current_work_location" class="form-label">Working Location</label>
              <input type="text" name="current_work_location" id="current_work_location" class="form-control" value="{{ $employee->current_work_location ?? '' }}" placeholder="Enter Location of Work (Will be Saved for next time)" required>
            </div>
            <div class="mb-3">
              <label for="joining_date" class="form-label">Joining Date</label>
              <input type="text" name="joining_date" id="joining_date" class="form-control disabled-input" value="{{ $employee->created_at->format('d-m-Y') }}" readonly required>
            </div>
          </div>


          <div class="col-md-6 pt-2" style="border-left: 1px solid black">
            <div class="mb-3">
              <label for="bank_name" class="form-label">Bank Name</label>
              <input type="text" name="bank_name" id="bank_name" class="form-control {{ $employee->bank_name ? 'disabled-input' : '' }}" value="{{ $employee->bank_name ?? '' }}" {{ $employee->bank_name ? 'readonly' : '' }} placeholder="Enter Bank Name (Will be Saved for next time)" required>
            </div>
            <div class="mb-3">
              <label for="account_number" class="form-label">Account Number</label>
              <input type="text" name="account_number" id="account_number" class="form-control {{ $employee->account_number ? 'disabled-input' : '' }}" value="{{ $employee->account_number ?? '' }}" {{ $employee->account_number ? 'readonly' : '' }} placeholder="Enter Account Number (Will be Saved for next time)" required>
            </div>
            <div class="mb-3">
              <label for="ifsc_code" class="form-label">IFSC Code</label>
              <input type="text" name="ifsc_code" id="ifsc_code" maxlength="11" pattern="[A-Z|a-z]{4}[0][A-Z0-9a-z]{6}"  class="form-control {{ $employee->ifsc_code ? 'disabled-input' : '' }}" value="{{ $employee->ifsc_code ?? '' }}" {{ $employee->ifsc_code ? 'readonly' : '' }} placeholder="Enter IFSC Code (Will be Saved for next time)"  required>
            </div>
            <div class="mb-3">
              <div class="row">
                <div class="col-6">
                  <label for="total_days" class="form-label">Total Days</label>
                  <input type="text" name="total_days" id="total_days" class="form-control disabled-input" value="" readonly required>
                </div>
                <div class="col-6">
                  <label for="paid_days" class="form-label">Pay Days</label>
                  <input type="number" name="paid_days" id="paid_days" class="form-control " min="1"  required>

                </div>
              </div>
            </div>
            <div class="mb-3">
              <div class="row">
                <div class="col-6">
                  <label for="ot_hours" class="form-label">OT Hours</label>
                  <input type="number" name="ot_hours" id="ot_hours" class="form-control" value="0"  required>
                </div>


              </div>
            </div>
          </div>
        </div>




      </div>
      <div class="card-body border-top border-bottom border-1 border-dark py-0 mt-5">
        <div class="row">
          <!-- Earning Section -->
          <div class="col-md-6 pt-2">
            <h3>Earning: </h3>
            <div class="mb-3">
              <label for="basic_salary" class="form-label">Basic Salary</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="basic_salary" id="basic_salary" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="hra" class="form-label">House Rent Allowance (HRA)</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="hra" id="hra" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="bonus" class="form-label">Bonus</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="bonus" id="bonus" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="other_earning" class="form-label">Other Earning</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="other_earning" id="other_earning" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="arrear" class="form-label">Arrear</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="arrear" id="arrear" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
          </div>

          <!-- Deduction Section -->
          <div class="col-md-6 pt-2" style="border-left: 1px solid black">
            <h3>Deduction: </h3>
            <div class="mb-3">
              <label for="provident_fund" class="form-label">Provident Fund (PF)</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="provident_fund" id="provident_fund" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="tax_deduction" class="form-label">Tax Deduction</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="tax_deduction" id="tax_deduction" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="accommodation" class="form-label">Accommodation</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="accommodation" id="accommodation" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="other_deduction" class="form-label">Other Deduction</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" step="1" min="0" name="other_deduction" id="other_deduction" class="form-control autoSelectInput" value="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="other_deduction_remark" class="form-label">Other Deduction Reason</label>
              <input type="text" name="other_deduction_remark" id="other_deduction_remark" class="form-control "  placeholder="Enter Reason of other deduction" >
            </div>
          </div>
        </div>



      </div>
      <div class="card-body" style="border-top: 2px solid black; border-bottom: 1px solid black; padding-top: 0; padding-bottom: 0;">
        <div class="row">
          <!-- Earning Section -->
          <div class="col-md-6 pt-2">
            <h5>Gross Salary: <span id="totalEarningsDisplay">₹ 0.00</span></h5>
          </div>
          <div class="col-md-6 pt-2" style="border-left: 1px solid black">
            <h5>Total Deduction: - <span id="totalDeductionsDisplay">₹ 0.00</span></h5>
          </div>

        </div>


      </div>
      <div class="card-body mt-4" style="border-top: 1px solid black; border-bottom: 1px solid black; padding-top: 0; padding-bottom: 0;">
        <div class="row">
          <div class="col-md-6 pt-2">
            <div class="p-3">
              <label for="mode_of_payment" class="form-label">Mode of Payment</label>
              <select name="mode_of_payment" id="mode_of_payment" class="form-select" required>
                <option value="">Select Payment Mode</option>
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cheque">Cheque</option>
                <option value="upi">UPI</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="p-3">
              <label for="transaction_id" class="form-label">Transaction ID / Reference Number</label>
              <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Enter transaction ID or reference number" required>
            </div>

            <div class="p-3">
              <label for="payment_screenshot" class="form-label">Upload Payment Screenshot (if available)</label>
              <input type="file" name="payment_screenshot" id="payment_screenshot" class="form-control" accept="image/*">
            </div>

            <div class="p-3">
              <label for="note" class="form-label">Note</label>
              <textarea name="note" id="note" class="form-control" placeholder="Any additional notes or remarks"></textarea>
            </div>
          </div>

          <div class="col-md-6 pt-2" style="border-left: 1px solid black">
            <div class="mb-3">
              <h5>Net Pay</h5>
              <h5><span id="netpay">₹ 0.00</span></h5>

            </div>
            <div class="mb-3">
              <label class="form-label" for="payment_date">Payment Date</label>
              <input type="date" name="payment_date" id="payment_date" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>

          </div>
        </div>
      </div>


      <button type="submit" class="btn btn-primary">Save Salary</button>
    </form>
  </div>
@endsection
@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const monthFromSelect = document.getElementById('salary_month_from');
      const yearFromSelect = document.getElementById('salary_year_from');
      const monthToSelect = document.getElementById('salary_month_to');
      const yearToSelect = document.getElementById('salary_year_to');
      const totalDaysInput = document.getElementById('total_days');

      document.querySelectorAll('[class*="autoSelectInput"]').forEach(element => {
        element.addEventListener('click', function() {
          this.select();
        });
      });

      function calculateTotalDays() {
        const monthMap = {
          January: 1, February: 2, March: 3, April: 4,
          May: 5, June: 6, July: 7, August: 8,
          September: 9, October: 10, November: 11, December: 12
        };


        const monthFrom = monthMap[monthFromSelect.value];
        const yearFrom = parseInt(yearFromSelect.value);
        const monthTo = monthMap[monthToSelect.value];
        const yearTo = parseInt(yearToSelect.value);

        if (!monthFrom || isNaN(yearFrom) || !monthTo || isNaN(yearTo)) {
          totalDaysInput.value = '';
          return;
        }

        const fromDate = new Date(yearFrom, monthFrom - 1, 1);
        const toDate = new Date(yearTo, monthTo, 0); // last day of the month

        const timeDifference = toDate - fromDate;
        const totalDays = Math.floor(timeDifference / (1000 * 3600 * 24)) + 1;

        totalDaysInput.value = totalDays > 0 ? totalDays : 0;
      }


      function calculateTotalEarnings() {
        const earningFields = ['basic_salary', 'hra', 'bonus', 'other_earning', 'arrear'];
        let total = 0;
        earningFields.forEach(id => {
          const value = parseFloat(document.getElementById(id).value) || 0;
          total += value;
        });
        document.getElementById('totalEarningsDisplay').innerText = '₹ ' + total.toFixed(2);
      }

      function calculateTotalDeductions() {
        const deductionFields = ['provident_fund', 'tax_deduction', 'accommodation', 'other_deduction'];
        let total = 0;
        deductionFields.forEach(id => {
          const value = parseFloat(document.getElementById(id).value) || 0;
          total += value;
        });
        document.getElementById('totalDeductionsDisplay').innerText = '₹ ' + total.toFixed(2);
      }
      function calculateNetPay() {
        const earningsText = document.getElementById('totalEarningsDisplay').innerText.replace(/[₹,\s]/g, '');
        const deductionsText = document.getElementById('totalDeductionsDisplay').innerText.replace(/[₹,\s]/g, '');

        const totalEarnings = parseFloat(earningsText) || 0;
        const totalDeductions = parseFloat(deductionsText) || 0;

        const netPay = totalEarnings - totalDeductions;
        document.getElementById('netpay').innerText = '₹ ' + netPay.toFixed(2);
      }

      const allInputIds = [
        'basic_salary', 'hra', 'bonus', 'other_earning', 'arrear',
        'provident_fund', 'tax_deduction', 'accommodation', 'other_deduction'
      ];

      allInputIds.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
          input.addEventListener('input', function() {
            calculateTotalEarnings();
            calculateTotalDeductions();
            calculateNetPay();

          });
        }
      });

      // Add change listeners for month and year dropdowns
      [monthFromSelect, yearFromSelect, monthToSelect, yearToSelect].forEach(select => {
        select.addEventListener('change', calculateTotalDays);
      });

      // Initialize on page load
      calculateTotalDays();
      calculateTotalEarnings();
      calculateTotalDeductions();
    });
  </script>
@endpush




