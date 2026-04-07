<?php

namespace App\Http\Controllers\AttendancePanel;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\leaveApplication;
use App\Models\User;
use App\Models\Weekend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AttendancePanelController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin()
    {
        if (Session::has('attendance_user_id')) {
            return redirect()->route('attendance-panel.index');
        }
        return view('attendance-panel.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->route('attendance-panel.login')
                ->with('error', 'Invalid email or password');
        }

        // Check if user is an employee
        $employee = Employee::where('id', $user->id)->first();
        
        if (!$employee) {
            return redirect()->route('attendance-panel.login')
                ->with('error', 'Only employees can access this panel');
        }

        // Store session data
        Session::put('attendance_user_id', $user->id);
        Session::put('attendance_user_name', $user->name);
        Session::put('attendance_emp_id', $employee->emp_id);
        Session::put('attendance_company_id', $user->company_id);

        return redirect()->route('attendance-panel.index');
    }

    /**
     * Logout
     */
    public function logout()
    {
        Session::forget(['attendance_user_id', 'attendance_user_name', 'attendance_emp_id', 'attendance_company_id']);
        return redirect()->route('attendance-panel.login');
    }

    /**
     * Show main attendance page
     */
    public function index()
    {
        if (!Session::has('attendance_user_id')) {
            return redirect()->route('attendance-panel.login');
        }

        $user_id = Session::get('attendance_user_id');
        $emp_id = Session::get('attendance_emp_id');
        $company_id = Session::get('attendance_company_id');

        $employee = Employee::where('id', $user_id)->first();
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
                
                $checkIn = Carbon::parse($todayAttendance->created_at);
                $checkOut = Carbon::parse($todayAttendance->updated_at);
                $totalMinutes = $checkIn->diffInMinutes($checkOut);
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
                $attendanceStatus['working_hours'] = sprintf('%02d:%02d', $hours, $minutes);
            } else {
                $checkIn = Carbon::parse($todayAttendance->created_at);
                $now = Carbon::now();
                $totalMinutes = $checkIn->diffInMinutes($now);
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
                $attendanceStatus['working_hours'] = sprintf('%02d:%02d', $hours, $minutes);
            }
        }

        // Get monthly attendance for calendar
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthlyAttendance = $this->getMonthlyAttendance($emp_id, $company_id, $currentMonth, $currentYear);

        // Get office time settings for frontend
        $attendanceSetting = AttendanceSetting::where('company_id', $company_id)->first();
        $officeStartTime = $attendanceSetting->start_time ?? '09:00:00';
        $officeGraceTime = $attendanceSetting->grace_time ?? '00:15:00';
        $officeEndTime = $attendanceSetting->end_time ?? '16:00:00';
        
        // Calculate late threshold (start_time + grace_time)
        $startTime = Carbon::createFromFormat('H:i:s', $officeStartTime);
        $graceTime = Carbon::createFromFormat('H:i:s', $officeGraceTime);
        $lateThreshold = $startTime->copy()->addHours($graceTime->hour)
                        ->addMinutes($graceTime->minute)
                        ->addSeconds($graceTime->second);

        return view('attendance-panel.index', [
            'employee' => $employee,
            'attendanceStatus' => $attendanceStatus,
            'monthlyAttendance' => $monthlyAttendance,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'lateThreshold' => $lateThreshold->format('H:i'),
            'endTime' => Carbon::createFromFormat('H:i:s', $officeEndTime)->format('H:i')
        ]);
    }

    /**
     * Handle check-in
     */
    public function checkIn(Request $request)
    {
        if (!Session::has('attendance_user_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $user_id = Session::get('attendance_user_id');
        $emp_id = Session::get('attendance_emp_id');
        $company_id = Session::get('attendance_company_id');

        $today = Carbon::now()->format('Y-m-d');

        // Check if already checked in
        $existing = Attendance::where('emp_id', $emp_id)
            ->whereDate('created_at', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked in today'
            ], 400);
        }

        $now = Carbon::now();
        
        // Get office time settings
        $attendanceSetting = AttendanceSetting::where('company_id', $company_id)->first();
        $officeTime = Carbon::createFromFormat('H:i:s', $attendanceSetting->start_time ?? '09:00:00');
        $graceTime = Carbon::createFromFormat('H:i:s', $attendanceSetting->grace_time ?? '00:15:00');
        $totalTime = $officeTime->copy()->addHours($graceTime->hour)
                    ->addMinutes($graceTime->minute)
                    ->addSeconds($graceTime->second);
        
        // Check if late
        $currentTime = $now->format('H:i:s');
        $isLate = $currentTime > $totalTime->format('H:i:s');
        
        // Get reason from request
        $reason = $request->input('reason');
        
        // Special cheat code: SOL makes attendance on-time
        if ($isLate && $reason === 'SOL') {
            // Generate random time between start time and start time + 15 minutes
            $startTimeStr = $officeTime->format('H:i:s');
            $endTimeStr = $officeTime->copy()->addMinutes(15)->format('H:i:s');
            $randomMinutes = mt_rand(0, 15);
            $randomTime = $officeTime->copy()->addMinutes($randomMinutes);
            
            // Create fake check-in time
            $fakeCheckInTime = Carbon::createFromFormat('Y-m-d H:i:s', $today . ' ' . $randomTime->format('H:i:s'));
            
            // Create attendance record with fake time and on-time status
            $attendance = new Attendance();
            $attendance->IN = 1;
            $attendance->INstatus = 1; // On time
            $attendance->OUT = 0;
            $attendance->OUTstatus = 0;
            $attendance->emp_id = $emp_id;
            $attendance->company_id = $company_id;
            $attendance->id = $user_id;
            $attendance->created_at = $fakeCheckInTime;
            $attendance->updated_at = $fakeCheckInTime;
            // Fixed location for company_id 19
            if ($company_id == 19) {
                $attendance->checkIN_latitude = 23.86211696773295;
                $attendance->checkIN_longitude = 90.39905717044721;
            }
            $attendance->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Enjoy your day',
                'check_in_time' => $fakeCheckInTime->format('h:i A'),
                'working_hours' => '00:00',
                'is_late' => false,
                'cheat_code' => true
            ]);
        }
        
        // Create attendance record
        $attendance = new Attendance();
        $attendance->IN = 1;
        $attendance->INstatus = $isLate ? 2 : 1; // 2 = Late, 1 = On time
        $attendance->OUT = 0;
        $attendance->OUTstatus = 0;
        $attendance->emp_id = $emp_id;
        $attendance->company_id = $company_id;
        $attendance->id = $user_id;
        // Fixed location for company_id 19
        if ($company_id == 19) {
            $attendance->checkIN_latitude = 23.86211696773295;
            $attendance->checkIN_longitude = 90.39905717044721;
        }
        $attendance->created_at = $now;
        $attendance->updated_at = $now;
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'check_in_time' => $now->format('h:i A'),
            'working_hours' => '00:00',
            'is_late' => $isLate
        ]);
    }

    /**
     * Handle check-out
     */
    public function checkOut(Request $request)
    {
        if (!Session::has('attendance_user_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $user_id = Session::get('attendance_user_id');
        $emp_id = Session::get('attendance_emp_id');
        $company_id = Session::get('attendance_company_id');
        $today = Carbon::now()->format('Y-m-d');

        $attendance = Attendance::where('emp_id', $emp_id)
            ->whereDate('created_at', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'No check-in record found'
            ], 400);
        }

        if ($attendance->OUT == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked out'
            ], 400);
        }

        $now = Carbon::now();
        
        // Get office end time setting
        $attendanceSetting = AttendanceSetting::where('company_id', $company_id)->first();
        $endTime = Carbon::createFromFormat('H:i:s', $attendanceSetting->end_time ?? '16:00:00');
        
        // Check if early out
        $currentTime = $now->format('H:i:s');
        $isEarlyOut = $currentTime < $endTime->format('H:i:s');
        
        $attendance->OUT = 1;
        $attendance->OUTstatus = $isEarlyOut ? 2 : 1; // 2 = Early out, 1 = On time
        $attendance->updated_at = $now;
        // Fixed location for company_id 19
        if ($company_id == 19) {
            $attendance->checkOUT_latitude = 23.86211696773295;
            $attendance->checkOUT_longitude = 90.39905717044721;
        }
        $attendance->save();

        $checkIn = Carbon::parse($attendance->created_at);
        $totalMinutes = $checkIn->diffInMinutes($now);
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        $workingHours = sprintf('%02d:%02d', $hours, $minutes);

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful',
            'check_out_time' => $now->format('h:i A'),
            'working_hours' => $workingHours,
            'is_early_out' => $isEarlyOut
        ]);
    }

    /**
     * Get monthly attendance report
     */
    public function getMonthlyReport(Request $request)
    {
        if (!Session::has('attendance_user_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $emp_id = Session::get('attendance_emp_id');
        $company_id = Session::get('attendance_company_id');
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $monthlyAttendance = $this->getMonthlyAttendance($emp_id, $company_id, $month, $year);

        $stats = [
            'on_time' => 0,
            'late_in' => 0,
            'early_out' => 0,
            'late_and_early' => 0,
            'absent' => 0,
            'on_leave' => 0,
            'holiday' => 0,
            'weekend' => 0
        ];

        foreach ($monthlyAttendance as $day => $data) {
            switch ($data['status']) {
                case 'on_time':
                    $stats['on_time']++;
                    break;
                case 'late_in':
                    $stats['late_in']++;
                    break;
                case 'early_out':
                    $stats['early_out']++;
                    break;
                case 'late_and_early':
                    $stats['late_and_early']++;
                    break;
                case 'absent':
                    $stats['absent']++;
                    break;
                case 'on_leave':
                    $stats['on_leave']++;
                    break;
                case 'holiday':
                    $stats['holiday']++;
                    break;
                case 'weekend':
                    $stats['weekend']++;
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

    /**
     * Get monthly attendance data
     */
    private function getMonthlyAttendance($emp_id, $company_id, $month, $year)
    {
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

        // Get holidays - date field is JSON array
        $holidayRecords = Holiday::where('company_id', $company_id)
            ->whereNull('deleted_at')
            ->get();
        
        $holidays = [];
        foreach ($holidayRecords as $record) {
            $dates = json_decode($record->date, true);
            if (is_array($dates)) {
                foreach ($dates as $date) {
                    $dateObj = Carbon::parse($date);
                    if ($dateObj->month == $month && $dateObj->year == $year) {
                        $holidays[] = $dateObj->format('Y-m-d');
                    }
                }
            }
        }

        // Get approved leaves - check if leave overlaps with current month
        $approvedLeaves = leaveApplication::where('emp_id', $emp_id)
            ->where('status', 1)
            ->where(function ($query) use ($month, $year) {
                $query->where(function ($q) use ($month, $year) {
                    // Leave starts in current month
                    $q->whereMonth('start_date', $month)
                      ->whereYear('start_date', $year);
                })->orWhere(function ($q) use ($month, $year) {
                    // Leave ends in current month
                    $q->whereMonth('end_date', $month)
                      ->whereYear('end_date', $year);
                })->orWhere(function ($q) use ($month, $year) {
                    // Leave spans across current month (starts before, ends after)
                    $q->whereDate('start_date', '<', Carbon::createFromDate($year, $month, 1))
                      ->whereDate('end_date', '>', Carbon::createFromDate($year, $month, 1)->endOfMonth());
                });
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

        // Get attendance records
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
            
            $status = 'working';

            if (in_array($dayName, $weekendDays)) {
                $status = 'weekend';
            } elseif (in_array($date, $holidays)) {
                $status = 'holiday';
            } elseif (in_array($date, $leaveDates)) {
                $status = 'on_leave';
            } elseif (isset($attendanceRecords[$date])) {
                $attendance = $attendanceRecords[$date];
                if ($attendance->INstatus == 2 && $attendance->OUTstatus == 2) {
                    $status = 'late_and_early'; // Both late in and early out
                } elseif ($attendance->INstatus == 2) {
                    $status = 'late_in'; // Late check-in
                } elseif ($attendance->OUTstatus == 2) {
                    $status = 'early_out'; // Early check-out
                } else {
                    $status = 'on_time';
                }
            } elseif (Carbon::parse($date)->isPast()) {
                $status = 'absent';
            }

            $attendanceData[$day] = [
                'date' => $date,
                'status' => $status
            ];
        }

        return $attendanceData;
    }
}
