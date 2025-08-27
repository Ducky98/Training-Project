<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Salary Slip - {{ $salary_id->company_name }}</title>
  <style>
    @media print {
      body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #FFFFFF;
      }
      .page {
        margin: 0;
        padding: 15px;
        box-shadow: none;
        border: none;
        min-height: auto;
      }
      .no-print {
        display: none !important;
      }
      .watermark {
        opacity: 0.07; /* Make watermark slightly more visible when printing */
      }
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
      color: #333;
      font-size: 12px;
    }

    .page {
      width: 210mm;
      max-height: 297mm;
      margin: 10mm auto;
      background: white;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      position: relative;
      padding: 15px;
      box-sizing: border-box;
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #003366;
      padding-bottom: 8px;
      margin-bottom: 15px;
    }

    .company-name {
      font-size: 22px;
      font-weight: bold;
      color: #003366;
      margin: 0;
    }

    .document-title {
      font-size: 16px;
      margin: 8px 0;
      color: #555;
    }

    .salary-period {
      font-size: 12px;
      font-weight: bold;
      margin: 4px 0;
    }

    .employee-details, .payment-details {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 5px;
      background-color: #f9f9f9;
    }

    .detail-group {
      width: 33.33%;
      margin-bottom: 8px;
      padding-right: 10px;
      box-sizing: border-box;
    }

    .detail-label {
      font-weight: bold;
      font-size: 10px;
      color: #666;
    }

    .detail-value {
      font-size: 12px;
    }

    .salary-details {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
    }

    .earnings, .deductions {
      width: 49%;
      border: 1px solid #ddd;
      border-radius: 5px;
      overflow: hidden;
    }

    .section-header {
      background-color: #003366;
      color: white;
      padding: 6px 10px;
      font-weight: bold;
      font-size: 12px;
    }

    .section-content {
      padding: 6px 10px;
    }

    .salary-row {
      display: flex;
      justify-content: space-between;
      padding: 3px 0;
      border-bottom: 1px solid #eee;
    }

    .summary {
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 15px;
      background-color: #f9f9f9;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 3px 0;
    }

    .net-pay {
      font-weight: bold;
      font-size: 14px;
      color: #003366;
      border-top: 1px solid #ddd;
      padding-top: 8px;
      margin-top: 6px;
    }

    .amount-in-words {
      font-style: italic;
      color: #555;
      margin: 10px 0;
      padding: 8px;
      border: 1px dashed #ccc;
      background-color: #f5f5f5;
      text-align: center;
      font-size: 12px;
    }

    .footer {
      margin-top: 20px;
      border-top: 1px solid #ddd;
      padding-top: 10px;
      font-size: 10px;
      color: #777;
      text-align: center;
    }

    .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .signature-box {
      width: 45%;
      text-align: center;
    }

    .signature-line {
      border-top: 1px solid #333;
      margin-top: 30px;
      padding-top: 5px;
      font-size: 12px;
    }

    .watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(0deg);
      z-index: 0;
      opacity: 0.05;
      pointer-events: none;
      width: 60%;
      height: auto;
    }

    .print-button {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      background-color: #003366;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .print-button:hover {
      background-color: #002244;
    }
  </style>
</head>
<body>
<div class="page">
  <img class="watermark" src="{{ asset('assets/svg/logo.svg') }}" alt="Watermark">

  <div class="header">
    <h1 class="company-name">{{ $salary_id->company_name }}</h1>
    <div class="document-title">SALARY SLIP</div>
    <div class="salary-period">For Period: {{ $salary_id->salary_period }}</div>
  </div>

  <div class="employee-details">
    <div class="detail-group">
      <div class="detail-label">Employee ID</div>
      <div class="detail-value">{{ $salary_id->employee_id }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Employee Name</div>
      <div class="detail-value">{{ $salary_id->employee_name }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Designation</div>
      <div class="detail-value">{{ $salary_id->designation }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Location</div>
      <div class="detail-value">{{ $salary_id->working_location }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Joining Date</div>
      <div class="detail-value">{{ date('d-m-Y', strtotime($salary_id->joining_date)) }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Payment Date</div>
      <div class="detail-value">{{ date('d-m-Y', strtotime($salary_id->payment_date)) }}</div>
    </div>
  </div>

  <div class="payment-details">
    <div class="detail-group">
      <div class="detail-label">Bank Name</div>
      <div class="detail-value">{{ $salary_id->bank_name }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Account Number</div>
      <div class="detail-value">{{ $salary_id->account_number }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">IFSC Code</div>
      <div class="detail-value">{{ $salary_id->ifsc_code }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Payment Mode</div>
      <div class="detail-value">{{ ucfirst($salary_id->mode_of_payment) }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Transaction ID</div>
      <div class="detail-value">{{ $salary_id->transaction_id }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Days in Month</div>
      <div class="detail-value">{{ $salary_id->total_days }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">Paid Days</div>
      <div class="detail-value">{{ $salary_id->paid_days }}</div>
    </div>
    <div class="detail-group">
      <div class="detail-label">OT Hours</div>
      <div class="detail-value">{{ $salary_id->ot_hours }}</div>
    </div>
  </div>

  <div class="salary-details">
    <div class="earnings">
      <div class="section-header">Earnings</div>
      <div class="section-content">
        <div class="salary-row">
          <div>Basic Salary</div>
          <div>₹ {{ number_format($salary_id->basic_salary, 2) }}</div>
        </div>
        <div class="salary-row">
          <div>HRA</div>
          <div>₹ {{ number_format($salary_id->hra, 2) }}</div>
        </div>
        <div class="salary-row">
          <div>Bonus</div>
          <div>₹ {{ number_format($salary_id->bonus, 2) }}</div>
        </div>
        <div class="salary-row">
          <div>Other Earning</div>
          <div>₹ {{ number_format($salary_id->other_earning, 2) }}</div>
        </div>
        <div class="salary-row">
          <div>Arrear</div>
          <div>₹ {{ number_format($salary_id->arrear, 2) }}</div>
        </div>
        <div class="salary-row" style="font-weight: bold;">
          <div>Total Earnings</div>
          <div>₹ {{ number_format($salary_id->total_earnings, 2) }}</div>
        </div>
      </div>
    </div>

    <div class="deductions">
      <div class="section-header">Deductions</div>
      <div class="section-content">
        <div class="salary-row">
          <div>Provident Fund</div>
          <div>₹ {{ number_format($salary_id->provident_fund, 2) }}</div>
        </div>
        <div class="salary-row">
          <div>Tax Deduction</div>
          <div>₹ {{ number_format($salary_id->tax_deduction, 2) }}</div>
        </div>
        <div class="salary-row">
          <div>Accommodation</div>
          <div>₹ {{ number_format($salary_id->accommodation, 2) }}</div>
        </div>
        <div class="salary-row">
          <div>Other Deduction</div>
          <div>₹ {{ number_format($salary_id->other_deduction, 2) }}</div>
        </div>
        @if($salary_id->other_deduction_remark)
          <div class="salary-row">
            <div><small>({{ $salary_id->other_deduction_remark }})</small></div>
            <div></div>
          </div>
        @else
          <div class="salary-row" style="visibility: hidden; height: 8px;">
            <div></div>
            <div></div>
          </div>
        @endif
        <div class="salary-row" style="font-weight: bold;">
          <div>Total Deductions</div>
          <div>₹ {{ number_format($salary_id->total_deductions, 2) }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="summary">
    <div class="summary-row">
      <div>Total Earnings</div>
      <div>₹ {{ number_format($salary_id->total_earnings, 2) }}</div>
    </div>
    <div class="summary-row">
      <div>Total Deductions</div>
      <div>- ₹ {{ number_format($salary_id->total_deductions, 2) }}</div>
    </div>
    <div class="summary-row net-pay">
      <div>Net Salary</div>
      <div>₹ {{ number_format($salary_id->net_pay, 2) }}</div>
    </div>
  </div>

  <div class="amount-in-words">
    {{ $amount_in_words }}
  </div>

  <div class="signatures">
    <div class="signature-box">
      <div class="signature-line">Employee Signature</div>
    </div>
    <div class="signature-box">
      <div class="signature-line">For {{ $salary_id->company_name }}</div>
    </div>
  </div>

  <div class="footer">
    <p>This is a computer-generated salary slip and does not require a signature.</p>
{{--    @if($salary_id->note)--}}
{{--      <p>Note: {{ $salary_id->note }}</p>--}}
{{--    @endif--}}
    <p>Please contact HR department for any discrepancies in this salary slip.</p>
  </div>
</div>

<button class="print-button no-print" onclick="window.print();">Print Salary Slip</button>
@if($salary_id->payment_screenshot)
  <div class="no-print" style="display: flex; align-items: center; flex-direction: column; margin-bottom: 2rem; ">
    <h3>Note</h3>
    <p>{{$salary_id->note}}</p>
    <h3>Payment Screenshot</h3>
    <img
      src="{{ asset('storage/' . $salary_id->payment_screenshot) }}"
      width="400px"
      alt="Payment Screenshot"
      onerror="this.onerror=null; this.src='{{ asset('assets/img/error-icon.png') }}'; this.alt='Image not found';"
    >
  </div>
@endif


<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Optional: You can add JavaScript functionality here if needed
  });
</script>
</body>
</html>
