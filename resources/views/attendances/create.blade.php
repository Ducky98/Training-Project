@extends('layouts.contentNavbarLayout')

@section('title', 'Record Attendance')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}">
@endsection

@section('vendor-script')
  <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Select2 for dropdowns
      $('.select2').select2({
        placeholder: 'Select an option',
        allowClear: true,
        templateResult: function(state) {
          if (!state.id) { return state.text; }
          // Optional: Add additional details to dropdown options
          return $('<span>' +
            state.text +
            (state.department ? ' <small>(' + state.department + ')</small>' : '') +
            '</span>');
        }
      });
// Auto-fill daily_rate based on selected employee's salary
      $('#employee_id').on('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const salary = selectedOption.getAttribute('data-salary');

        document.getElementById('daily_rate').value = salary ?? '';
      });


      // Initialize Flatpickr for date selection
      const dateInput = flatpickr("#dates", {
        mode: "multiple",
        dateFormat: "d-m-Y",
        maxDate: new Date().fp_incr(90), // 3 months from now
        onClose: function(selectedDates, dateStr, instance) {
          const selectedDatesContainer = document.getElementById('selected-dates');
          const hiddenInputsContainer = document.getElementById('hidden-dates-container');

          // Clear existing selections
          selectedDatesContainer.innerHTML = '';
          hiddenInputsContainer.innerHTML = '';

          // Add new selections
          selectedDates.forEach(date => {
            const formattedDate = instance.formatDate(date, 'd-m-Y');

            // Create date tag
            const dateTag = document.createElement('div');
            dateTag.className = 'badge bg-label-primary me-2 mb-2';
            dateTag.innerHTML = `
                    ${formattedDate}
                    <i class="bx bx-x ms-1 remove-date" data-date="${formattedDate}"></i>
                `;

            // Add remove functionality
            dateTag.querySelector('.remove-date').addEventListener('click', function() {
              dateInput.removeDate(date);
            });

            // Add to selected dates
            selectedDatesContainer.appendChild(dateTag);

            // Create hidden input
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'dates[]';
            hiddenInput.value = formattedDate;
            hiddenInputsContainer.appendChild(hiddenInput);
          });
        }
      });

      // Form validation
      document.getElementById('attendance-form').addEventListener('submit', function(e) {
        let isValid = true;
        const employeeSelect = document.getElementById('employee_id');
        const selectedDates = document.querySelectorAll('#hidden-dates-container input');

        // Clear previous error states
        employeeSelect.classList.remove('is-invalid');

        // Validate employee selection
        if (!employeeSelect.value) {
          employeeSelect.classList.add('is-invalid');
          isValid = false;
        }

        // Validate date selection
        if (selectedDates.length === 0) {
          Swal.fire({
            icon: 'error',
            title: 'Date Selection Required',
            text: 'Please select at least one date for attendance'
          });
          isValid = false;
        }

        if (!isValid) {
          e.preventDefault();
        }
      });
    });
  </script>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Record Attendance</h5>
            <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-outline-secondary">
              <i class="bx bx-list-ul me-1"></i> View All Attendances
            </a>
          </div>

          <div class="card-body">
            <form id="attendance-form" method="POST" action="{{ route('attendances.store') }}">
              @csrf

              <div class="row mb-3">
                <label for="employee_id" class="col-sm-3 col-form-label">Employee <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                  <select
                    id="employee_id"
                    name="employee_id"
                    class="form-select select2 @error('employee_id') is-invalid @enderror"
                    required
                  >
                    <option value="">Select Employee</option>
                    @foreach($users as $employee)
                      <option
                        value="{{ $employee->id }}"
                        data-salary="{{ $employee->current_salary }}"
                        data-department="{{ $employee->department->name ?? '' }}"
                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                      >
                        {{ $employee->full_name }} ({{ $employee->employee_code }})
                      </option>
                    @endforeach
                  </select>

                  @error('employee_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row mb-3">
                <label for="shift_id" class="col-sm-3 col-form-label">Shift <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                  <select
                    id="shift_id"
                    name="shift_id"
                    class="form-select select2 @error('shift_id') is-invalid @enderror"
                    required
                  >
                    @foreach($shifts as $shift)
                      <option
                        value="{{ $shift->id }}"
                        {{ (old('shift_id', 3) == $shift->id) ? 'selected' : '' }}
                      >
                        {{ $shift->name }} ({{ $shift->hours }} hours)
                      </option>

                    @endforeach
                  </select>
                  @error('shift_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row mb-3">
                <label for="dates" class="col-sm-3 col-form-label">Dates <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                  <input
                    type="text"
                    id="dates"
                    class="form-control @error('dates') is-invalid @enderror"
                    placeholder="Select Dates"
                  >
                  <div id="selected-dates" class="mt-2 d-flex flex-wrap">
                    @if(old('dates'))
                      @foreach(old('dates') as $date)
                        <span class="badge bg-label-primary me-2 mb-2">
                                                {{ $date }}
                                                <i class="bx bx-x ms-1 remove-date" data-date="{{ $date }}"></i>
                                            </span>
                      @endforeach
                    @endif
                  </div>
                  <div id="hidden-dates-container" style="display:none;">
                    @if(old('dates'))
                      @foreach(old('dates') as $date)
                        <input type="hidden" name="dates[]" value="{{ $date }}">
                      @endforeach
                    @endif
                  </div>
                  @error('dates')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="form-text text-muted">
                    <i class="bx bx-info-circle me-1"></i>
                    Select multiple dates. Dates can be up to 3 months in advance.
                  </small>
                </div>
              </div>

              <div class="row mb-3">
                <label for="status" class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                  <select
                    id="status"
                    name="status"
                    class="form-select @error('status') is-invalid @enderror"
                    required
                  >
                    <option value="">Select Status</option>
                    <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>
                      Present
                    </option>
                    <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>
                      Absent
                    </option>
                    <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>
                      Late
                    </option>
                  </select>
                  @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="row mb-3">
                <label for="daily_rate" class="col-sm-3 col-form-label">Daily Rate</label>
                <div class="col-sm-9">
                  <input
                    type="number"
                    id="daily_rate"
                    name="daily_rate"
                    class="form-control @error('daily_rate') is-invalid @enderror"
                    step="0.01"
                    placeholder="Enter daily rate"
                    value="{{ old('daily_rate') }}"
                  >
                  @error('daily_rate')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row mb-3">
                <label for="notes" class="col-sm-3 col-form-label">Notes</label>
                <div class="col-sm-9">
                                <textarea
                                  id="notes"
                                  name="notes"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Additional notes (optional)"
                                >{{ old('notes') }}</textarea>
                  @error('notes')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-sm-9 offset-sm-3">
                  <button type="submit" class="btn btn-primary me-2">
                    <i class="bx bx-save me-1"></i> Save Attendance
                  </button>
                  <button type="reset" class="btn btn-outline-secondary">
                    <i class="bx bx-reset me-1"></i> Reset
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-style')
  <style>
    .remove-date {
      cursor: pointer;
      margin-left: 5px;
    }
    .remove-date:hover {
      color: #ff0000;
    }
  </style>
@endsection
