<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
  public function index(Request $request)
  {
    $users = Employee::all();
    $shifts = Shift::all();

    $month = $request->month ? Carbon::parse($request->month) : Carbon::now();
    $startOfMonth = $month->copy()->startOfMonth();
    $endOfMonth = $month->copy()->endOfMonth();

    $attendances = Attendance::with('shift')
      ->whereBetween('date', [$startOfMonth, $endOfMonth])
      ->get()
      ->groupBy('employee_id')
      ->map(function ($userAttendances) {
        return $userAttendances->keyBy(function ($attendance) {
          return $attendance->date->format('Y-m-d');
        });
      });

    // Calculate attendance statistics for each user
    $attendanceStats = [];
    foreach ($users as $user) {
      $userAttendances = Attendance::where('employee_id', $user->id)
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->get();

      $attendanceStats[$user->id] = [
        'total_days' => $userAttendances->count(),
        'present_days' => $userAttendances->where('status', 'present')->count(),
        'absent_days' => $userAttendances->where('status', 'absent')->count(),
        'late_days' => $userAttendances->where('status', 'late')->count(),
        'total_earnings' => $userAttendances->sum('daily_rate')
      ];
    }

    $calendar = collect();
    $currentDate = $startOfMonth->copy();

    while ($currentDate <= $endOfMonth) {
      $calendar->push($currentDate->copy());
      $currentDate->addDay();
    }

    return view('attendances.index', compact('users', 'shifts', 'attendances', 'calendar', 'month', 'attendanceStats'));
  }

  public function create()
  {
    $users = Employee::all();
    $shifts = Shift::all();
    return view('attendances.create', compact('users', 'shifts'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'employee_id' => 'required|exists:employees,id',
      'shift_id' => 'required|exists:shifts,id',
      'dates' => 'required|array',
      'dates.*' => 'date_format:d-m-Y',
      'notes' => 'nullable|string|max:500',
      'status' => 'required|in:present,absent,late',
      'daily_rate' => 'nullable|numeric|min:0'
    ]);

    // Convert dates to proper Y-m-d format for database storage
    $formattedDates = array_map(function($date) {
      return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
    }, $validated['dates']);

    $attendanceData = [
      'employee_id' => $validated['employee_id'],
      'shift_id' => $validated['shift_id'],
      'status' => $validated['status'],
      'notes' => $validated['notes'] ?? null,
      'daily_rate' => $validated['daily_rate'] ?? null,
    ];

    foreach ($formattedDates as $date) {
      Attendance::updateOrCreate(
        [
          'employee_id' => $validated['employee_id'],
          'date' => $date,
        ],
        array_merge($attendanceData, ['date' => $date])
      );
    }
    if (!is_null($validated['daily_rate'])) {
      $employee = Employee::find($validated['employee_id']);
      if ($employee) {
        $employee->current_salary = $validated['daily_rate'];
        $employee->save();
      }
    }
    return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully');
  }

  public function edit(Attendance $attendance)
  {
    $users = Employee::all();
    $shifts = Shift::all();
    return view('attendances.edit', compact('attendance', 'users', 'shifts'));
  }

  public function update(Request $request, Attendance $attendance)
  {
    $validated = $request->validate([
      'employee_id' => 'required|exists:employees,id',
      'shift_id' => 'required|exists:shifts,id',
      'date' => 'required|date',
      'notes' => 'nullable|string',
      'status' => 'required|in:present,absent,late',
      'daily_rate' => 'nullable|numeric|min:0'
    ]);

    $attendance->update($validated);

    return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully');
  }

  public function destroy(Attendance $attendance)
  {
    $attendance->delete();

    return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully');
  }

  public function markAttendance(Request $request)
  {
    $user_id = Auth::id();
    $currentDate = Carbon::today();

    $validated = $request->validate([
      'shift_id' => 'required|exists:shifts,id',
    ]);

    $attendance = Attendance::firstOrNew([
      'user_id' => $user_id,
      'date' => $currentDate,
    ]);

    $attendance->shift_id = $validated['shift_id'];

    if (!$attendance->clock_in) {
      $attendance->clock_in = Carbon::now()->format('H:i');
      $attendance->status = 'present';
    } else {
      $attendance->clock_out = Carbon::now()->format('H:i');
    }

    $attendance->save();

    return redirect()->back()->with('success', 'Attendance marked successfully');
  }

  public function bulkDelete(Request $request)
  {
    $request->validate([
      'user_id' => 'required|exists:employees,id',
      'date_range' => 'required|string'
    ]);

    $userId = $request->user_id;
    $dateRange = explode(' to ', $request->date_range);
    $startDate = $dateRange[0];
    $endDate = $dateRange[1] ?? $dateRange[0];

    Attendance::where('employee_id', $userId)
      ->whereBetween('date', [$startDate, $endDate])
      ->delete();

    return redirect()->back()->with('success', 'Attendance records deleted successfully');
  }

}
