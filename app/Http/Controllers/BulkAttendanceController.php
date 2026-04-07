<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\leaveApplication;
use App\Models\LeaveApprove;
use App\Models\User;
use App\Models\Weekend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BulkAttendanceController extends Controller
{
    /**
     * Bulk add attendance for user_id = 78 from March 21 to April 7, 2026
     * Attendance time: Random between 10:00 AM and 10:30 AM
     * Attendance checkout time: Random between 07:00 PM and 08:30 PM
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

        // Date range: March 21, 2026 to April 7, 2026
        $startDate = Carbon::create(2026, 3, 21);
        $endDate = Carbon::create(2026, 4, 7);

        // Delete existing attendance records for this user in the date range
        $deletedCount = Attendance::where('emp_id', $emp_id)
            ->whereBetween('created_at', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d') . ' 23:59:59'])
            ->delete();

        Log::info('Existing attendance deleted before bulk add', [
            'user_id' => $user_id,
            'emp_id' => $emp_id,
            'date_range' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
            'deleted_count' => $deletedCount
        ]);

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

        // Get holidays for the company within date range (JSON date format)
        $holidayRecords = Holiday::where('company_id', $company_id)
            ->whereNull('deleted_at')
            ->get();
        
        $holidays = [];
        foreach ($holidayRecords as $record) {
            $dates = json_decode($record->date, true);
            if (is_array($dates)) {
                foreach ($dates as $date) {
                    $holidayDate = Carbon::parse($date)->format('Y-m-d');
                    if ($holidayDate >= $startDate->format('Y-m-d') && $holidayDate <= $endDate->format('Y-m-d')) {
                        $holidays[] = $holidayDate;
                    }
                }
            }
        }

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

            // Generate random check-in time between 10:00 AM and 10:30 AM
            $randomMinutes = mt_rand(0, 30);
            $randomSeconds = mt_rand(0, 59);
            $checkInTime = Carbon::create(
                $currentLoopDate->year,
                $currentLoopDate->month,
                $currentLoopDate->day,
                10, // 10 AM
                $randomMinutes,
                $randomSeconds
            );

            // Generate random check-out time between 07:00 PM and 08:30 PM
            $randomOutMinutes = mt_rand(0, 90); // 0 to 90 minutes (7:00 PM to 8:30 PM)
            $randomOutSeconds = mt_rand(0, 59);
            $checkOutTime = Carbon::create(
                $currentLoopDate->year,
                $currentLoopDate->month,
                $currentLoopDate->day,
                19, // 7 PM
                $randomOutMinutes,
                $randomOutSeconds
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
            $attendance->created_at = $checkInTime;
            $attendance->updated_at = $checkOutTime;
            $attendance->save();

            $addedAttendanceIds[] = [
                'attendance_id' => $attendance->attendance_id,
                'date' => $dateStr,
                'check_in_time' => $checkInTime->format('H:i:s'),
                'check_out_time' => $checkOutTime->format('H:i:s')
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
            'attendance_deleted' => $deletedCount,
            'attendance_added' => $addedCount,
            'added_attendance_ids' => $addedAttendanceIds,
            'skipped_count' => count($skippedDates),
            'skipped_dates' => $skippedDates
        ], 201);
    }

    /**
     * Approve all pending leaves for user_id = 78
     */
    public function approveAllPendingLeavesForUser()
    {
        $user_id = 78;
        $employee = Employee::where('id', $user_id)->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $emp_id = $employee->emp_id;

        // Get all pending leave applications for this employee
        $pendingLeaves = leaveApplication::where('emp_id', $emp_id)
            ->where('status', 0) // 0 = pending
            ->get();

        if ($pendingLeaves->isEmpty()) {
            return response()->json([
                'message' => 'No pending leaves found for this employee',
                'user_id' => $user_id,
                'emp_id' => $emp_id
            ], 200);
        }

        $approvedLeaves = [];

        foreach ($pendingLeaves as $leave) {
            // Update leave application status to approved (1)
            $leave->status = 1;
            $leave->approvel_date = Carbon::now()->format('Y-m-d');
            $leave->approval_name = 'Bulk Approved';
            $leave->save();

            // Also update all related LeaveApprove records to approved
            LeaveApprove::where('leave_application_id', $leave->leave_application_id)
                ->update(['status' => 1]);

            $approvedLeaves[] = [
                'leave_application_id' => $leave->leave_application_id,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'count' => $leave->count
            ];
        }

        // Log the approved leaves
        Log::info('Bulk Leave Approval', [
            'user_id' => $user_id,
            'emp_id' => $emp_id,
            'total_approved' => count($approvedLeaves),
            'approved_leaves' => $approvedLeaves
        ]);

        return response()->json([
            'message' => 'All pending leaves approved successfully',
            'user_id' => $user_id,
            'emp_id' => $emp_id,
            'total_approved' => count($approvedLeaves),
            'approved_leaves' => $approvedLeaves
        ], 200);
    }
}
