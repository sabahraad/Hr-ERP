<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\leaveApplication;
use App\Models\Weekend;
use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;

class reportController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth:api');
    // }

    public function customAttendanceReport(Request $request){
        $company_id = auth()->user()->company_id;
        $empList = Employee::where('employees.company_id',$company_id)
                            ->join("users", "users.id", "=", "employees.id")
                            ->join("departments","departments.dept_id","=","employees.dept_id")
                            ->join("designations","designations.designation_id","=","employees.designation_id")
                            ->get(['employees.*', 'users.email','departments.deptTitle','designations.desigTitle']);
        // dd(count($empList));
        $result= [];
        foreach($empList as $emp){
            $emp_id = $emp->emp_id;
            $daysInMonth = Carbon::createFromDate($request->year, $request->month, 1)->daysInMonth;
            $dateList = [];
            $attendanceList=[];
            $data = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {

                $currentDate = Carbon::createFromDate($request->year, $request->month, $day);
                $date= "$request->year-$request->month-$day";
                // dd($date);
                $dateToCheck = Carbon::parse($date);
                $dateValue = $currentDate->toDateString();
                // Weekend check
                $isWeekend = Weekend::where(strtolower($currentDate->format('l')), 1)->where('company_id',$company_id)->exists();

                // hoilday check
                $isHoliday = Holiday::where('date', $currentDate->toDateString())->where('company_id',$company_id)->exists();

                // leave check
                $isLeave = leaveApplication::where('dateArray', 'like', "%{$currentDate->toDateString()}%")->where('status',1)->where('emp_id',$emp_id)->exists();
                
                if ($isWeekend) {
                // Weekend
                    $attendanceList['late'] = false;
                    $attendanceList['present'] = false;
                    $attendanceList['Absent'] = false;
                    $attendanceList['leave'] = false;
                    $attendanceList['weekend'] = true;
                    $attendanceList['holiday'] = false;
                    $attendanceList['workingDay'] = false;
                } elseif ($isHoliday) {
                // Holiday
                    $attendanceList['late'] = false;
                    $attendanceList['present'] = false;
                    $attendanceList['Absent'] = false;
                    $attendanceList['leave'] = false;
                    $attendanceList['weekend'] = false;
                    $attendanceList['holiday'] = true;
                    $attendanceList['workingDay'] = false;

                } elseif ($isLeave) {
                // Leave
                    $attendanceList['late'] = false;
                    $attendanceList['present'] = false;
                    $attendanceList['Absent'] = false;
                    $attendanceList['leave'] = true;
                    $attendanceList['weekend'] = false;
                    $attendanceList['holiday'] = false;
                    $attendanceList['workingDay'] = true;

                } else {
                // Working day
                    if($dateToCheck->isFuture()){
                        $attendanceList['late'] = false;
                        $attendanceList['present'] = false;
                        $attendanceList['Absent'] = false;
                        $attendanceList['leave'] = false;
                        $attendanceList['weekend'] = false;
                        $attendanceList['holiday'] = false;
                        $attendanceList['workingDay'] = true;
                    }else{
                        //attendance list
                        $attendanceDetails = Attendance::whereDate('created_at', $date)->where('emp_id',$emp_id)->first();
                        // dd($attendanceDetails);
                        if($attendanceDetails == null){
                            $attendanceList['late'] = false;
                            $attendanceList['present'] = false;
                            $attendanceList['Absent'] = true;
                            $attendanceList['leave'] = false;
                            $attendanceList['weekend'] = false;
                            $attendanceList['holiday'] = false;
                            $attendanceList['workingDay'] = true;
                        }elseif($attendanceDetails->INstatus == 2){
                            $attendanceList['late'] = true;
                            $attendanceList['present'] = true;
                            $attendanceList['Absent'] = false;
                            $attendanceList['leave'] = false;
                            $attendanceList['weekend'] = false;
                            $attendanceList['holiday'] = false;
                            $attendanceList['workingDay'] = true;
                        }else{
                            $attendanceList['late'] = false;
                            $attendanceList['present'] = true;
                            $attendanceList['Absent'] = false;
                            $attendanceList['leave'] = false;
                            $attendanceList['weekend'] = false;
                            $attendanceList['holiday'] = false;
                            $attendanceList['workingDay'] = true;
                        }
                    }
                    
                }
                    
                $data[$dateValue] = $attendanceList;
            }
            // dd($data,$dateList);
            $absentCount = 0;
            $LateCount = 0;
            $workingDays = 0;
            $weekends = 0;
            $hoildays = 0;
            $leaves = 0;
            $present = 0;
            foreach ($data as $date => $details) {
                if ($details['Absent']) {
                    $absentCount++;
                }
                if ($details['late']) {
                    $LateCount++;
                }
                if ($details['workingDay']) {
                    $workingDays++;
                }
                if ($details['weekend']) {
                    $weekends++;
                }
                if ($details['holiday']) {
                    $hoildays++;
                }
                if ($details['leave']) {
                    $leaves++;
                }
                if ($details['present']) {
                    $present++;
                }
            }
            $emp['absentCount'] = $absentCount;
            $emp['LateCount'] = $LateCount;
            $emp['workingDays'] = $workingDays;
            $emp['weekends'] = $weekends;
            $emp['hoildays'] = $hoildays;
            $emp['leaves'] = $leaves;
            $emp['present'] = $present;
            $result[] = $emp;
            dd($result);
        }
        dd($result);
        $currentDate = Carbon::now();
       


// return $januaryReport;
    }

    public function uploadEmployees(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');

        Excel::import(new EmployeesImport, $file);

        return redirect('/uploadexcel')->with('success', 'Employees imported successfully.');
    }

    public function uploadexcel(){
        return view('excelUpload');
    }
}
