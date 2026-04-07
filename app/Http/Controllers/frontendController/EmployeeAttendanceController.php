<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\leaveApplication;
use App\Models\Weekend;
use App\Utils\BaseUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmployeeAttendanceController extends Controller
{
    protected $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = BaseUrl::get();
    }

    /**
     * Show the employee self-service attendance page
     */
    public function myAttendance()
    {
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $user_id = session('id');
        
        if (!$access_token) {
            return redirect()->route('loginForm');
        }

        // Get employee details
        $employee = Employee::where('id', $user_id)->first();
        
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee not found');
        }

        $emp_id = $employee->emp_id;
        $today = Carbon::now()->format('Y-m-d');

        // Check today's attendance status
        $todayAttendance = Attendance::where('emp_id', $emp_id)
            ->whereDate('created_at', $today)
            ->first();

        $attendanceStatus = [
            'checked_in' => false,
            'checked_out' => false,
            'check_in_time' => null,
            'check_out_time' => null,
            'working_hours' => '00:00'
        ];

        if ($todayAttendance) {
            $attendanceStatus['checked_in'] = true;
            $attendanceStatus['check_in_time'] = Carbon::parse($todayAttendance->created_at)->format('h:i A');
            
            if ($todayAttendance->OUT == 1) {
                $attendanceStatus['checked_out'] = true;
                $attendanceStatus['check_out_time'] = Carbon::parse($todayAttendance->updated_at)->format('h:i A');
                
                // Calculate working hours
                $checkIn = Carbon::parse($todayAttendance->created_at);
                $checkOut = Carbon::parse($todayAttendance->updated_at);
                $totalMinutes = $checkIn->diffInMinutes($checkOut);
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
                $attendanceStatus['working_hours'] = sprintf('%02d:%02d Hr\'s', $hours, $minutes);
            } else {
                // Calculate working hours so far
                $checkIn = Carbon::parse($todayAttendance->created_at);
                $now = Carbon::now();
                $totalMinutes = $checkIn->diffInMinutes($now);
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
                $attendanceStatus['working_hours'] = sprintf('%02d:%02d Hr\'s', $hours, $minutes);
            }
        }

        // Get monthly attendance summary for calendar
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthlyAttendance = $this->getMonthlyAttendance($emp_id, $currentMonth, $currentYear);

        return view('frontend.myAttendance', [
            'jwtToken' => $access_token,
            'baseUrl' => $baseUrl,
            'employee' => $employee,
            'attendanceStatus' => $attendanceStatus,
            'monthlyAttendance' => $monthlyAttendance,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear
        ]);
    }

    /**
     * Get monthly attendance data for calendar view
     */
    private function getMonthlyAttendance($emp_id, $month, $year)
    {
        $company_id = session('company_id');
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $attendanceData = [];

        // Get weekends
        $weekendData = Weekend::where('company_id', $company_id)->first();
        $weekendDays = [];
        if ($weekendData) {
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach ($days as $day) {
                if ($weekendData->$day == 1) {
                    $weekendDays[] = $day;
                }
            }
        }

        // Get holidays for the month
        $holidays = Holiday::where('company_id', $company_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        // Get approved leaves for the month
        $approvedLeaves = leaveApplication::where('emp_id', $emp_id)
            ->where('status', 1)
            ->where(function ($query) use ($month, $year) {
                $query->whereMonth('start_date', $month)
                    ->whereYear('start_date', $year);
            })
            ->get();

        $leaveDates = [];
        foreach ($approvedLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);
            while ($leaveStart->lte($leaveEnd)) {
                $leaveDates[] = $leaveStart->format('Y-m-d');
                $leaveStart->addDay();
            }
        }

        // Get all attendance records for the month
        $attendanceRecords = Attendance::where('emp_id', $emp_id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            });

        // Build calendar data
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
            $dayName = Carbon::createFromDate($year, $month, $day)->format('l');
            
            $status = 'working'; // default
            $label = '';

            if (in_array($dayName, $weekendDays)) {
                $status = 'weekend';
            } elseif (in_array($date, $holidays)) {
                $status = 'holiday';
            } elseif (in_array($date, $leaveDates)) {
                $status = 'on_leave';
            } elseif (isset($attendanceRecords[$date])) {
                $attendance = $attendanceRecords[$date];
                if ($attendance->INstatus == 2 || $attendance->OUTstatus == 2) {
                    $status = 'late';
                    $label = 'Late';
                } else {
                    $status = 'on_time';
                    $label = 'On Time';
                }
            } elseif (Carbon::parse($date)->isPast()) {
                $status = 'absent';
            }

            $attendanceData[$day] = [
                'date' => $date,
                'status' => $status,
                'label' => $label
            ];
        }

        return $attendanceData;
    }

    /**
     * Handle check-in
     */
    public function checkIn(Request $request)
    {
        $user_id = session('id');
        $company_id = session('company_id');
        
        $employee = Employee::where('id', $user_id)->first();
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $emp_id = $employee->emp_id;
        $today = Carbon::now()->format('Y-m-d');

        // Check if already checked in today
        $existingAttendance = Attendance::where('emp_id', $emp_id)
            ->whereDate('created_at', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked in today'
            ], 400);
        }

        // Create new attendance record
        $now = Carbon::now();
        $attendance = new Attendance();
        $attendance->IN = 1;
        $attendance->INstatus = 1; // On time (you can modify based on office time settings)
        $attendance->OUT = 0;
        $attendance->OUTstatus = 0;
        $attendance->emp_id = $emp_id;
        $attendance->company_id = $company_id;
        $attendance->id = $user_id;
        $attendance->created_at = $now;
        $attendance->updated_at = $now;
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'check_in_time' => $now->format('h:i A'),
            'working_hours' => '00:00 Hr\'s'
        ]);
    }

    /**
     * Handle check-out
     */
    public function checkOut(Request $request)
    {
        $user_id = session('id');
        
        $employee = Employee::where('id', $user_id)->first();
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $emp_id = $employee->emp_id;
        $today = Carbon::now()->format('Y-m-d');

        // Find today's attendance record
        $attendance = Attendance::where('emp_id', $emp_id)
            ->whereDate('created_at', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'No check-in record found for today'
            ], 400);
        }

        if ($attendance->OUT == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked out today'
            ], 400);
        }

        // Update attendance record with check-out
        $now = Carbon::now();
        $attendance->OUT = 1;
        $attendance->OUTstatus = 1; // On time
        $attendance->updated_at = $now;
        $attendance->save();

        // Calculate working hours
        $checkIn = Carbon::parse($attendance->created_at);
        $checkOut = $now;
        $totalMinutes = $checkIn->diffInMinutes($checkOut);
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        $workingHours = sprintf('%02d:%02d Hr\'s', $hours, $minutes);

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful',
            'check_out_time' => $now->format('h:i A'),
            'working_hours' => $workingHours
        ]);
    }

    /**
     * Get attendance report for a specific month
     */
    public function getMonthlyReport(Request $request)
    {
        $user_id = session('id');
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $employee = Employee::where('id', $user_id)->first();
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $monthlyAttendance = $this->getMonthlyAttendance($employee->emp_id, $month, $year);

        // Calculate summary statistics
        $stats = [
            'on_time' => 0,
            'late' => 0,
            'absent' => 0,
            'on_leave' => 0,
            'working_days' => 0
        ];

        foreach ($monthlyAttendance as $day => $data) {
            switch ($data['status']) {
                case 'on_time':
                    $stats['on_time']++;
                    $stats['working_days']++;
                    break;
                case 'late':
                    $stats['late']++;
                    $stats['working_days']++;
                    break;
                case 'absent':
                    $stats['absent']++;
                    $stats['working_days']++;
                    break;
                case 'on_leave':
                    $stats['on_leave']++;
                    break;
                case 'working':
                    $stats['working_days']++;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'attendance' => $monthlyAttendance,
            'stats' => $stats,
            'month' => $month,
            'year' => $year
        ]);
    }
}
