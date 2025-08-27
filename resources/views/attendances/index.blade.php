@extends('layouts/contentNavbarLayout')

@section('title', 'Attendance Calendar')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center" style="position: sticky; top: 0; background-color: #fff; z-index: 10;">
            <h3>Attendance Calendar</h3>
            <div>
              <a href="{{ route('attendances.create') }}" class="btn btn-primary">Record Attendance</a>
            </div>
          </div>


          <div class="card-body">
            <!-- Month Navigation -->
            <div class="row mb-4">
              <div class="col-md-4">
                <a href="{{ route('attendances.index', ['month' => $month->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-primary">&laquo; Previous Month</a>
              </div>
              <div class="col-md-4 text-center">
                <h4>{{ $month->format('F Y') }}</h4>
              </div>
              <div class="col-md-4 text-right">
                <a href="{{ route('attendances.index', ['month' => $month->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-primary">Next Month &raquo;</a>
              </div>
            </div>

            <!-- Attendance Statistics -->
            <div class="row mb-4">
              <div class="col-12">
                <h5>Attendance Statistics</h5>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                  <table class="table table-bordered mb-0">
                    <thead class="thead-light" style="position: sticky; top: 0; background: white; z-index: 1;">
                    <tr>
                      <th>Employee</th>
                      <th>Present Days</th>
                      <th>Total Days</th>
                      <th>Per Day Pay</th>
                      <th>Total Earnings</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                      <tr>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $attendanceStats[$user->id]['present_days'] }}</td>
                        <td>{{ $attendanceStats[$user->id]['total_days'] }}</td>
                        <td>{{$user->current_salary ?? 'N/A'}}</td>
                        <td>{{ number_format($attendanceStats[$user->id]['total_earnings'], 2) }}</td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>




            <!-- Calendar View -->
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                <tr>
                  <th>Employee</th>
                  @foreach($calendar as $date)
                    <th class="{{ $date->isWeekend() ? 'bg-light' : '' }}">
                      <div>{{ $date->format('d') }}</div>
                      <small>{{ $date->format('D') }}</small>
                    </th>
                  @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                  <tr>
                    <td>
                      <div class="mb-2">{{ $user->first_name.' '.$user->last_name }}</div>

                      <!-- Mass Delete Form -->
                      <form action="{{ route('attendances.mass-delete') }}" method="POST" class="mass-delete-form" id="mass-delete-form-{{ $user->id }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="input-group mb-2">
                          <input type="text" class="form-control form-control-sm date-range-picker"
                                 name="date_range" id="date-range-{{ $user->id }}"
                                 placeholder="Select dates to delete">
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash"></i> Delete Selected
                        </button>
                      </form>
                    </td>

                    @foreach($calendar as $date)
                      <td class="{{ $date->isWeekend() ? 'bg-light' : '' }}">
                        @if(isset($attendances[$user->id][$date->format('Y-m-d')]))
                          @php
                            $attendance = $attendances[$user->id][$date->format('Y-m-d')];
                            $shift = $attendance->shift;
                            $status = $attendance->status;

                            // Determine background color based on status
                            $bgColor = '#28a745'; // Default green for Present
                            if ($status == 'Absent') {
                                $bgColor = '#dc3545'; // Red for Absent
                            } elseif ($status == 'Late') {
                                $bgColor = '#ffc107'; // Yellow/amber for Late
                            }
                          @endphp

                          <div style="background-color: {{ $bgColor }}; color: white; padding: 5px; border-radius: 3px; text-align: center;">
                            <span>{{ $status }}</span>
                          </div>

                          <small class="d-block mt-1 text-center">
                            {{ $shift->name }}

                          </small>

                        @else
                          <a href="{{ route('attendances.create', ['user_id' => $user->id, 'date' => $date->format('Y-m-d')]) }}" class="text-muted">
                            <i class="fas fa-plus-circle"></i>
                          </a>
                        @endif
                      </td>
                    @endforeach
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>

            <!-- Status and Shift Legend -->
            <div class="mt-4">
              <div class="row">
                <div class="col-md-6">
                  <h5>Attendance Status</h5>
                  <div class="d-flex flex-wrap">
                    <div class="mr-3 mb-2">
                      <span style="background-color: #28a745; color: white; padding: 3px 8px; border-radius: 3px;">
                          Present
                      </span>
                    </div>
                    <div class="mr-3 mb-2">
                      <span style="background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 3px;">
                          Absent
                      </span>
                    </div>
                    <div class="mr-3 mb-2">
                      <span style="background-color: #ffc107; color: white; padding: 3px 8px; border-radius: 3px;">
                          Late
                      </span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <h5>Shift Types</h5>
                  <div class="d-flex flex-wrap">
                    @foreach($shifts as $shift)
                      <div class="mr-3 mb-2">
                        <span style="background-color: #6c757d; color: white; padding: 3px 8px; border-radius: 3px;">
                            {{ $shift->name }} ({{ $shift->hours }} hours)
                        </span>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection
@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Flatpickr date range pickers
      document.querySelectorAll('.date-range-picker').forEach(function(element) {
        flatpickr(element, {
          mode: "range",
          dateFormat: "Y-m-d",
          minDate: "{{ $month->startOfMonth()->format('Y-m-d') }}",
          maxDate: "{{ $month->endOfMonth()->format('Y-m-d') }}",
          defaultDate: ["{{ $month->startOfMonth()->format('Y-m-d') }}", "{{ $month->endOfMonth()->format('Y-m-d') }}"],
        });
      });

      // Confirm before submitting mass delete forms
      document.querySelectorAll('.mass-delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
          const dateRange = this.querySelector('.date-range-picker').value;
          if (!dateRange) {
            e.preventDefault();
            alert('Please select a date range first');
            return;
          }

          if (!confirm('Are you sure you want to delete attendance records for the selected date range? This action cannot be undone.')) {
            e.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
