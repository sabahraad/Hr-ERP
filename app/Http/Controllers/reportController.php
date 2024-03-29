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
        // $result = DB::table('employees')
        //             ->select(
        //                 'employees.name',
        //                 'employees.officeEmployeeID',
        //                 'departments.deptTitle',
        //                 'designations.desigTitle',
        //                 DB::raw('COUNT(*) AS total__present_days'),
        //                 DB::raw('SUM(CASE WHEN attendances.INstatus = 1 THEN 1 ELSE 0 END) AS ontime_checkIN_days'),
        //                 DB::raw('SUM(CASE WHEN attendances.INstatus = 2 THEN 1 ELSE 0 END) AS late_checkIN_days'),
        //                 DB::raw('SUM(CASE WHEN attendances.OUTstatus = 1 THEN 1 ELSE 0 END) AS ontime_checkout_days'),
        //                 DB::raw('SUM(CASE WHEN attendances.OUTstatus = 2 THEN 1 ELSE 0 END) AS early_checkout_days')
        //             )
        //             ->join('departments', 'departments.dept_id', '=', 'employees.dept_id')
        //             ->join('designations', 'designations.designation_id', '=', 'employees.designation_id')
        //             ->join('attendances', 'employees.emp_id', '=', 'attendances.emp_id')
        //             ->where('employees.company_id', '=', $company_id)
        //             ->whereDate('attendances.created_at', '>=', $startDate)
        //             ->whereDate('attendances.created_at', '<=', $endDate)
        //             ->groupBy('employees.name', 'employees.officeEmployeeID', 'departments.deptTitle', 'designations.desigTitle')
        //             ->get();

        $result = DB::table('employees')
                    ->select(
                        'employees.name',
                        'employees.officeEmployeeID',
                        'departments.deptTitle',
                        'designations.desigTitle',
                        DB::raw('COUNT(*) AS total_present_days'),
                        DB::raw('SUM(CASE WHEN attendances.INstatus = 1 THEN 1 ELSE 0 END) AS ontime_checkIN_days'),
                        DB::raw('SUM(CASE WHEN attendances.INstatus = 2 THEN 1 ELSE 0 END) AS late_checkIN_days'),
                        DB::raw('SUM(CASE WHEN attendances.OUTstatus = 1 THEN 1 ELSE 0 END) AS ontime_checkout_days'),
                        DB::raw('SUM(CASE WHEN attendances.OUTstatus = 2 THEN 1 ELSE 0 END) AS early_checkout_days'),
                        DB::raw('COALESCE(leave_days.total_days, 0) AS total_leave_days')
                    )
                    ->join('departments', 'departments.dept_id', '=', 'employees.dept_id')
                    ->join('designations', 'designations.designation_id', '=', 'employees.designation_id')
                    ->join('attendances', 'employees.emp_id', '=', 'attendances.emp_id')
                    ->leftJoin(DB::raw('(
                            SELECT emp_id, SUM(IF(MONTH(date), 1, 0)) AS total_days
                            FROM (
                                SELECT emp_id, JSON_UNQUOTE(JSON_EXTRACT(dateArray, CONCAT(\'$[\', numbers.n, \']\'))) AS date
                                FROM leave_applications
                                JOIN (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3) AS numbers
                                WHERE DATE_FORMAT(JSON_UNQUOTE(JSON_EXTRACT(dateArray, CONCAT(\'$[\', numbers.n, \']\'))), \'%Y-%m\') = \'2024-02\'
                            ) AS subquery
                            GROUP BY emp_id
                        ) AS leave_days'), 'employees.emp_id', '=', 'leave_days.emp_id')
                    ->where('employees.company_id', '=', $company_id)
                    ->whereDate('attendances.created_at', '>=', $startDate)
                    ->whereDate('attendances.created_at', '<=', $endDate)
                    ->groupBy('employees.name', 'employees.officeEmployeeID', 'departments.deptTitle', 'designations.desigTitle',
                    'leave_days.total_days')
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
