<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    public function customAttendanceReport(Request $request){
        $company_id = auth()->user()->company_id;
        $date = $request->date_range;
        $dateParts = explode(' - ', $date);
        $startDate = $dateParts[0];
        $endDate = $dateParts[1];
        $result = DB::table('employees')
                    ->select(
                        'employees.name',
                        'employees.officeEmployeeID',
                        'departments.deptTitle',
                        'designations.desigTitle',
                        DB::raw('COUNT(*) AS total__present_days'),
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
                    ->groupBy('employees.name', 'employees.officeEmployeeID', 'departments.deptTitle', 'designations.desigTitle')
                    ->get();
        if(count($result) == 0){
            return response()->json([
                'message'=>'Attendance Report Not Found',
                'data'=>$result
            ],400);
        }else{
            return response()->json([
                'message'=>'Attendance Report',
                'data'=>$result
            ],200);
        }
    }

}
