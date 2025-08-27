<div class="card mb-6">
  <div class="card-body pt-12">
    <div class="user-avatar-section">
      <div class="d-flex align-items-center flex-column">
        <div class="profile-photo-container position-relative">
          <img
            class="img-fluid rounded mb-4"
            src="{{ $employee->avatar ? asset('storage/' . $employee->avatar) : asset('assets/img/avatars/1.png') }}"
            height="120"
            width="120"
            id="profile-photo"
            alt="User avatar"
          />
          <a href="{{route('admin.employee.edit_profile_photo', $employee->employee_id)}}" class="overlay">
            <i class="ri-pencil-fill"></i>
          </a>
        </div>
        <div class="user-info text-center">
          <h5>{{ ucwords($employee->first_name .' '. $employee->last_name) }}</h5>
        </div>
      </div>
    </div>

    <div
      class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4"
    >
      <div class="d-flex align-items-center me-5 gap-4">
        <div class="avatar">
          <div
            class="avatar-initial bg-label-primary rounded"
          >
            <i class="ri-check-line ri-24px"></i>
          </div>
        </div>
        <div>
          <h5 class="mb-0">10</h5>
          <span>Patient Operated</span>
        </div>
      </div>
      <div class="d-flex align-items-center gap-4">
        <div class="avatar">
          <div
            class="avatar-initial bg-label-primary rounded"
          >
            <i class="ri-star-fill ri-24px"></i>
          </div>
        </div>
        <div>
          <h5 class="mb-0">4/5</h5>
          <span>Rating</span>
        </div>
      </div>
    </div>

    <h5 class="">Details</h5>
    <div class="info-container">
      <ul class="list-unstyled mb-6">
        <li class="mb-2">
          <span class="h6">Employee Id:</span>
          <span>{{$employee->employee_id ?? "Not Available"}}</span>
        </li>
        <li class="mb-2">
          <span class="h6">Email:</span>
          <span>{{$employee->email ?? "Not Available"}}</span>
        </li>
        <li class="mb-2">
          <span class="h6">Status:</span>
          <span class="badge bg-label-success rounded-pill"
          >Active</span
          >
        </li>
        <li class="mb-2">
          <span class="h6">Contact:</span>
          <span>{{$employee->mobile_number ?? "Not Available"}}</span>
        </li>
      </ul>
      <div class="d-flex justify-content-center gap-4">
        {{-- Call Button --}}
        <a href="{{ $employee->mobile_number ? 'tel:+91' . $employee->mobile_number : '#' }}"
           class="btn {{ $employee->mobile_number ? 'btn-primary' : 'btn-secondary' }} rounded-circle d-flex align-items-center justify-content-center"
           style="width: 45px; height: 45px; {{ $employee->mobile_number ? '' : 'pointer-events: none; opacity: 0.5;' }}"
           title="Call" target="_blank">
          <i class="ri-phone-fill"></i>
        </a>

        {{-- WhatsApp Button --}}
        <a href="{{ $employee->whatsapp_number ? 'https://wa.me/+91' . $employee->whatsapp_number : '#' }}"
           class="btn {{ $employee->whatsapp_number ? 'btn-success' : 'btn-secondary' }} rounded-circle d-flex align-items-center justify-content-center"
           style="width: 45px; height: 45px; {{ $employee->whatsapp_number ? '' : 'pointer-events: none; opacity: 0.5;' }}"
           title="WhatsApp" target="_blank">
          <i class="ri-whatsapp-line"></i>
        </a>

        {{-- Email Button --}}
        <a href="{{ $employee->email ? 'mailto:' . $employee->email : '#' }}"
           class="btn {{ $employee->email ? 'btn-danger' : 'btn-secondary' }} rounded-circle d-flex align-items-center justify-content-center"
           style="width: 45px; height: 45px; {{ $employee->email ? '' : 'pointer-events: none; opacity: 0.5;' }}"
           title="Email" target="_blank">
          <i class="ri-mail-fill"></i>
        </a>
      </div>
      <div class="d-flex justify-content-between gap-4 mt-3">
        {{-- WhatsApp Button --}}
        @php
        $employeeIdCardUrl = url(route('admin.employee.idCard', ['employee_id' => Hashids::encode($employee->id)]));
        @endphp
        <a href="https://wa.me/?text={{ rawurlencode('Click the link to view the Employee ID Card: [Click Here](' . $employeeIdCardUrl . ')') }}"
           class="btn btn-success rounded d-flex align-items-center px-3"
           style="width: auto; height: 45px;"
           title="Share on WhatsApp" target="_blank">
          <i class="ri-whatsapp-fill fs-5 me-1"></i>
          <span class="fw-bold">Share on WhatsApp</span>
        </a>



        {{-- Id Print Button --}}
        <button
          class="btn btn-primary rounded d-flex align-items-center px-3"
          style="width: auto; height: 45px;"
          data-bs-toggle="modal"
          data-bs-target="#companySelectModal"
          data-employee="{{ Hashids::encode($employee->id) }}"
          title="Print ID Card">
          <i class="ri-id-card-fill fs-5 me-1"></i>
          <span class="fw-bold">Print ID Card</span>
        </button>

      </div>


      <div class="modal fade" id="companySelectModal" tabindex="-1" aria-labelledby="companySelectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Select Company</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            @php
              $companies = json_decode(\App\Models\AdminConfigurations::where('key', 'companies')->value('value'), true);

            @endphp
              <div class="mb-3">
                <label for="companySelect" class="form-label">Select Company</label>
                <select class="form-select" id="companySelect">
                  <option selected disabled>Select Company</option>
                  @foreach($companies as $index => $company)
                    <option value="{{ $index }}">{{ $company['name'] }}</option>
                  @endforeach
                </select>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" id="proceedPrint" class="btn btn-primary">Proceed</button>
            </div>
          </div>
        </div>
      </div>
      <script>
        let selectedEmployeeId = '';

        // Capture the employee ID when the button is clicked
        document.querySelectorAll('[data-bs-target="#companySelectModal"]').forEach(btn => {
          btn.addEventListener('click', function () {
            selectedEmployeeId = this.getAttribute('data-employee');
          });
        });

        // Proceed button click handler
        document.getElementById('proceedPrint').addEventListener('click', function () {
          const select = document.getElementById('companySelect');
          const selectedCompanyIndex = select.value;

          if (!selectedCompanyIndex) {
            alert('Please select a company');
            return;
          }

          const selectedCompanyName = select.options[select.selectedIndex].text;

          const url = `{{ route('admin.employee.idCard', ['employee_id' => '__EMPLOYEE__']) }}?company=${encodeURIComponent(selectedCompanyName)}`;
          const finalUrl = url.replace('__EMPLOYEE__', selectedEmployeeId);

          window.open(finalUrl, '_blank');
        });
      </script>




    </div>
  </div>
</div>
