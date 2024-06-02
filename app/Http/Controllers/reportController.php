<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Weekend;
use App\Models\Holiday;

class reportController extends Controller
{
    public function customAttendanceReport(Request $request){
        $company_id = auth()->user()->company_id;
        $date = $request->date_range;
        $dateParts = explode(' - ', $date);
        $startDate = $dateParts[0];
        $endDate = $dateParts[1];

        // Array to hold working days
        $workingDays = [];

        $currentDate = Carbon::parse($startDate);
        $endDateTime = Carbon::parse($endDate);

        // Loop through each date in the range
        while ($currentDate <= $endDateTime) {
            $currentDateString = $currentDate->toDateString();

            // Check if the current date is a weekend, holiday, or leave day
            $isWeekend = Weekend::where(strtolower($currentDate->format('l')), 1)->where('company_id',$company_id)->exists();

            // Check if current date is a holiday
            $isHoliday = Holiday::where('company_id', $company_id)
                ->whereJsonContains('date', $currentDateString)
                ->exists();

            // Add to working days if it's not a weekend, holiday, or leave day
            if (!$isWeekend && !$isHoliday ) {
                $workingDays[] = $currentDateString;
            }
            // Move to the next day
            $currentDate->addDay();
        }

        // Count the number of working days
        $workingDaysCount = count($workingDays);

        if($request->dept_id){
            $result = DB::table('employees')
                    ->select(
                        'employees.name',
                        'employees.emp_id',
                        'employees.officeEmployeeID',
                        'departments.deptTitle',
                        'designations.desigTitle',
                        DB::raw('COUNT(*) AS total_present_days'),
                        DB::raw('SUM(CASE WHEN attendances.INstatus = 1 THEN 1 ELSE 0 END) AS ontime_checkIN_days'),
                        DB::raw('SUM(CASE WHEN attendances.INstatus = 2 THEN 1 ELSE 0 END) AS late_checkIN_days'),
                        DB::raw('SUM(CASE WHEN attendances.OUTstatus = 1 THEN 1 ELSE 0 END) AS ontime_checkout_days'),
                        DB::raw('SUM(CASE WHEN attendances.OUTstatus = 2 THEN 1 ELSE 0 END) AS early_checkout_days')
                    )
                    ->join('departments', 'departments.dept_id', '=', 'employees.dept_id')
                    ->join('designations', 'designations.designation_id', '=', 'employees.designation_id')
                    ->join('attendances', 'employees.emp_id', '=', 'attendances.emp_id')
                    ->where('employees.company_id', '=', $company_id)
                    ->where('employees.dept_id', '=', $request->dept_id)
                    ->whereDate('attendances.created_at', '>=', $startDate)
                    ->whereDate('attendances.created_at', '<=', $endDate)
                    ->groupBy('employees.name','employees.emp_id', 'employees.officeEmployeeID', 'departments.deptTitle', 'designations.desigTitle'
                    )
                    ->get();
            foreach ($result as $item) {
                // Add the 'workingDays' key with the value of 30 to each item
                $item->absentDays = $workingDaysCount - $item->total_present_days;
                $item->workingDays = $workingDaysCount;
            }
        }else{
            $result = DB::table('employees')
                    ->select(
                        'employees.name',
                        'employees.emp_id',
                        'employees.officeEmployeeID',
                        'departments.deptTitle',
                        'designations.desigTitle',
                        DB::raw('COUNT(*) AS total_present_days'),
                        DB::raw('SUM(CASE WHEN attendances.INstatus = 1 THEN 1 ELSE 0 END) AS ontime_checkIN_days'),
                        DB::raw('SUM(CASE WHEN attendances.INstatus = 2 THEN 1 ELSE 0 END) AS late_checkIN_days'),
                        DB::raw('SUM(CASE WHEN attendances.OUTstatus = 1 THEN 1 ELSE 0 END) AS ontime_checkout_days'),
                        DB::raw('SUM(CASE WHEN attendances.OUTstatus = 2 THEN 1 ELSE 0 END) AS early_checkout_days')
                    )
                    ->join('departments', 'departments.dept_id', '=', 'employees.dept_id')
                    ->join('designations', 'designations.designation_id', '=', 'employees.designation_id')
                    ->join('attendances', 'employees.emp_id', '=', 'attendances.emp_id')
                    ->where('employees.company_id', '=', $company_id)
                    ->whereDate('attendances.created_at', '>=', $startDate)
                    ->whereDate('attendances.created_at', '<=', $endDate)
                    ->groupBy('employees.name','employees.emp_id', 'employees.officeEmployeeID', 'departments.deptTitle', 'designations.desigTitle'
                    )
                    ->get();
            foreach ($result as $item) {
                // Add the 'workingDays' key with the value of 30 to each item
                $item->absentDays = $workingDaysCount - $item->total_present_days;
                $item->workingDays = $workingDaysCount;
            }
        }
        
                
        if(count($result) == 0){
            return response()->json([
                'message'=>'Attendance Report Not Found',
                'data'=>$result
            ],400);
        }else{
            return response()->json([
                'message'=>'Attendance Report',
                'data'=>$result,
                'startDate' => $startDate,
                'endDate' => $endDate
            ],200);
        }
    }

    public function customLeaveReport(Request $request){
        $company_id = auth()->user()->company_id;
        $date = $request->date_range;
        $dateParts = explode(' - ', $date);
        $startDate = $dateParts[0];
        $endDate = $dateParts[1];
        $employees = Employee::where('employees.company_id', $company_id)
                            ->join('leave_applications', 'employees.emp_id', '=', 'leave_applications.emp_id')
                            ->join('leave_settings', 'leave_applications.leave_setting_id', '=', 'leave_settings.leave_setting_id')
                            ->where(function($query) use ($startDate, $endDate) {
                                $query->whereBetween('leave_applications.start_date', [$startDate, $endDate])
                                    ->orWhereBetween('leave_applications.end_date', [$startDate, $endDate]);
                            })
                            ->select(
                                'employees.name',
                                'employees.officeEmployeeID',
                                'employees.emp_id',
                                DB::raw('SUM(leave_applications.count) AS leaveApplication_count'),
                                'leave_applications.dateArray',
                                'leave_settings.leave_type'
                            )
                            ->groupBy('employees.name', 'employees.officeEmployeeID', 'employees.emp_id', 'leave_settings.leave_type','leave_applications.dateArray')
                            ->get();
        return response()->json([
            'message'=>'leave Report',
            'data'=>$employees
        ],200);
    }
}
