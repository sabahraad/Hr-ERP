<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\leaveApplication;
use App\Models\User;
use App\Models\Weekend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BulkAttendanceController extends Controller
{
    /**
     * Bulk add attendance for user_id = 78 from Nov 01 to Jan 31
     * Attendance time: Random between 10:00 AM and 10:15 AM
     * Checks: Weekends, Holidays, Approved Leaves, Existing Attendance
     */
    public function bulkAddAttendanceForUser()
    {
        $user_id = 78;
        $employee = Employee::where('id', $user_id)->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $emp_id = $employee->emp_id;
        $company_id = User::find($user_id)->company_id;

        // Date range: Nov 01, 2025 to Jan 31, 2026
        $startDate = Carbon::create(2025, 11, 1);
        $endDate = Carbon::create(2026, 1, 31);

        // Get weekends for the company
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

        // Get holidays for the company within date range
        $holidays = Holiday::where('company_id', $company_id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        // Get approved leaves for the employee within date range
        $approvedLeaves = leaveApplication::where('emp_id', $emp_id)
            ->where('status', 1) // approved
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            })
            ->get();

        // Build array of leave dates
        $leaveDates = [];
        foreach ($approvedLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);
            while ($leaveStart->lte($leaveEnd)) {
                $leaveDates[] = $leaveStart->format('Y-m-d');
                $leaveStart->addDay();
            }
        }

        // Get existing attendance dates for the employee
        $existingAttendance = Attendance::where('emp_id', $emp_id)
            ->whereBetween('created_at', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d') . ' 23:59:59'])
            ->get()
            ->map(function ($attendance) {
                return Carbon::parse($attendance->created_at)->format('Y-m-d');
            })
            ->toArray();

        $addedCount = 0;
        $addedAttendanceIds = [];
        $skippedDates = [];
        $currentLoopDate = $startDate->copy();

        while ($currentLoopDate->lte($endDate)) {
            $dateStr = $currentLoopDate->format('Y-m-d');
            $dayName = $currentLoopDate->format('l');

            // Check if it's a weekend
            if (in_array($dayName, $weekendDays)) {
                $skippedDates[] = ['date' => $dateStr, 'reason' => 'Weekend'];
                $currentLoopDate->addDay();
                continue;
            }

            // Check if it's a holiday
            if (in_array($dateStr, $holidays)) {
                $skippedDates[] = ['date' => $dateStr, 'reason' => 'Holiday'];
                $currentLoopDate->addDay();
                continue;
            }

            // Check if employee is on leave
            if (in_array($dateStr, $leaveDates)) {
                $skippedDates[] = ['date' => $dateStr, 'reason' => 'On Leave'];
                $currentLoopDate->addDay();
                continue;
            }

            // Check if attendance already exists
            if (in_array($dateStr, $existingAttendance)) {
                $skippedDates[] = ['date' => $dateStr, 'reason' => 'Attendance Exists'];
                $currentLoopDate->addDay();
                continue;
            }

            // Generate random time between 10:00 AM and 10:15 AM
            $randomMinutes = mt_rand(0, 15);
            $randomSeconds = mt_rand(0, 59);
            $checkInTime = Carbon::create(
                $currentLoopDate->year,
                $currentLoopDate->month,
                $currentLoopDate->day,
                10,
                $randomMinutes,
                $randomSeconds
            );

            // Create attendance record
            $attendance = new Attendance();
            $attendance->IN = 1;
            $attendance->INstatus = 1; // On time
            $attendance->OUT = 1;
            $attendance->OUTstatus = 1; // On time
            $attendance->emp_id = $emp_id;
            $attendance->company_id = $company_id;
            $attendance->id = $user_id;
            $attendance->edit_reason = 'Bulk attendance added';
            $attendance->created_at = $checkInTime;
            $attendance->updated_at = $checkInTime->copy()->addHours(9); // Assuming 9 hour workday
            $attendance->save();

            $addedAttendanceIds[] = [
                'attendance_id' => $attendance->attendance_id,
                'date' => $dateStr,
                'check_in_time' => $checkInTime->format('H:i:s')
            ];

            $addedCount++;
            $currentLoopDate->addDay();
        }

        // Log the added attendance IDs
        Log::info('Bulk Attendance Added', [
            'user_id' => $user_id,
            'emp_id' => $emp_id,
            'company_id' => $company_id,
            'date_range' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
            'total_added' => $addedCount,
            'attendance_ids' => $addedAttendanceIds
        ]);

        return response()->json([
            'message' => 'Bulk attendance added successfully',
            'user_id' => $user_id,
            'emp_id' => $emp_id,
            'company_id' => $company_id,
            'date_range' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
            'attendance_added' => $addedCount,
            'added_attendance_ids' => $addedAttendanceIds,
            'skipped_count' => count($skippedDates),
            'skipped_dates' => $skippedDates
        ], 201);
    }
}
