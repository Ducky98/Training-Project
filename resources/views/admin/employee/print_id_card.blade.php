<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee ID Card</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f0f0f0;
    }

    .page {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .id-card {
      width: 320px;
      height: 480px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      overflow: hidden;
      position: relative;
    }

    .header {
      background-color: #0046ad;
      color: white;
      text-align: center;
      padding: 20px 0;
      border-bottom: 5px solid #ff9c00;
    }

    .company-name {
      font-size: 20px;
      font-weight: bold;
      margin: 0;
      text-transform: uppercase;
    }

    .card-title {
      font-size: 14px;
      margin: 5px 0 0;
    }

    .photo-section {
      text-align: center;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }

    .photo-placeholder {
      width: 100px;
      height: 100px;
      background-color: #f0f0f0;
      border-radius: 50%;
      margin: 0 auto;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      border: 1px solid #ddd;
    }

    .photo-placeholder img {
      width: 100%;
      height: auto;
    }

    .details {
      padding: 15px;
    }

    .employee-name {
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
    }

    .detail-row {
      display: flex;
      margin-bottom: 8px;
    }

    .detail-label {
      font-weight: bold;
      width: 110px;
      color: #555;
      font-size: 12px;
    }

    .detail-value {
      flex: 1;
      font-size: 12px;
    }

    .footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      background-color: #0046ad;
      color: white;
      text-align: center;
      padding: 8px 0;
      font-size: 10px;
    }

    .barcode {
      text-align: center;
      padding: 10px 0;
      margin-bottom: 30px;
    }

    .barcode svg {
      width: 100%;
      height: 40px;
      max-width: 280px;
    }

    .download-btn {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #0046ad;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .download-btn:hover {
      background-color: #003380;
    }

    @media print {
      body {
        background-color: white;
        padding: 0;
      }

      .page {
        height: 100vh;
      }

      .id-card {
        box-shadow: none;
        border: 1px solid #ddd;
      }

      .download-btn {
        display: none;
      }
    }
  </style>
  <!-- Add JsBarcode library for reliable barcode generation -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
  <!-- Add html2canvas for converting the ID card to an image -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>
<div class="page">
  <div class="id-card" id="idCard">
    <div class="header">
      <p class="company-name">{{$company['company_name']}}</p>
      <p class="card-title">{{$company['company_address']}}</p>
      <p class="card-title">{{$company['company_location']}}</p>
    </div>

    <div class="photo-section">
      <div class="photo-placeholder">
        <img src="{{ $employee->avatar ? asset('storage/' . $employee->avatar) : asset('assets/img/avatars/1.png') }}" alt="Employee Photo">
      </div>
    </div>

    <div class="details">
      <div class="employee-name">
        <span>{{ $employee->first_name }} {{ $employee->last_name }}</span>
      </div>

      <div class="detail-row">
        <div class="detail-label">Employee ID:</div>
        <div class="detail-value">{{ $employee->employee_id }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Gender:</div>
        <div class="detail-value">{{ $employee->gender }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Category :</div>
        <div class="detail-value">{{ $employee->category }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Aadhar No:</div>
        <div class="detail-value">
          @if($employee->aadhar_number)
            ********{{ substr($employee->aadhar_number, -4) }}
          @else
            Not Available
          @endif
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Address:</div>
        <div class="detail-value">{{ $employee->address }}, {{ $employee->state }}, {{ $employee->country }}</div>
      </div>

      <div class="barcode">
        <svg id="barcode"></svg>
      </div>
    </div>

    <div class="footer">
      <p>If found, please return to {{ $company['company_name'] }}, {{ $company['company_address'] }}</p>
    </div>
  </div>

  <button class="download-btn" id="downloadBtn">Download ID Card</button>
</div>
@if ($employee_documents->isNotEmpty())
  <div class="aadhaar-section">
    <h3 style="text-align: center; margin-top: 15px;">Aadhaar Documents</h3>
    <div class="aadhaar-images" style="display: flex; justify-content: center;">
      @foreach ($employee_documents as $document)
        <div class="aadhaar-card">
          <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->document_type }}" style="max-width: 300px">
          <center>{{ ucfirst($document->document_type) }}</center>
        </div>
      @endforeach
    </div>
  </div>
@endif
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Generate barcode using JsBarcode library
    JsBarcode("#barcode", "{{ $employee->employee_id }}", {
      format: "CODE39",
      width: 1.5,
      height: 40,
      displayValue: true,
      fontSize: 12,
      margin: 5,
      background: "#ffffff" // Ensure background is white
    });

    // Add download functionality
    document.getElementById('downloadBtn').addEventListener('click', function() {
      const idCard = document.getElementById('idCard');
      const employeeName = "{{ $employee->first_name }}_{{ $employee->last_name }}";
      const fileName = "ID_Card_" + employeeName + ".png";

      // Use html2canvas to convert the ID card div to an image
      html2canvas(idCard, {
        scale: 2, // Higher scale for better quality
        backgroundColor: "#ffffff",
        logging: false,
        useCORS: true // Allow images from different domains
      }).then(function(canvas) {
        // Create a download link
        const link = document.createElement('a');
        link.download = fileName;
        link.href = canvas.toDataURL('image/png');
        link.click();
      });
    });
  });
</script>
</body>
</html>
