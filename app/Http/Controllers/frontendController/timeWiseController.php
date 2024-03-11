<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\leaveApplication;
use App\Models\Weekend;
use App\Models\Holiday;
use Illuminate\Database\QueryException;
use Exception;
use Carbon\Carbon;

class timeWiseController extends Controller
{
    public function timeWise(){
        return view('timewise');
    }

    public function dashboard(){
        $access_token = session('access_token');
        $company_id = session('company_id');
        $total_emp = Employee::where('company_id',$company_id)
                              ->where('deleted_at',null)
                              ->count();
        $today = Carbon::now()->toDateString();
        $total_attendance = Attendance::where('company_id', $company_id)
                                        ->where('created_at', '>=', $today . ' 00:00:00')
                                        ->where('created_at', '<=', $today . ' 23:59:59')
                                        ->where('deleted_at', null)
                                        ->count();
        $total_on_leave = LeaveApplication::join('employees', 'leave_applications.emp_id', '=', 'employees.emp_id')
                                            ->where('employees.company_id', $company_id)
                                            ->where('leave_applications.status', 1)
                                            ->whereJsonContains('leave_applications.dateArray', $today)
                                            ->count();
        $total_absent = $total_emp - ($total_attendance + $total_on_leave);
        $data = [
            'total_emp' => $total_emp,
            'total_attendance' => $total_attendance,
            'total_on_leave' => $total_on_leave,
            'total_absent' => $total_absent
        ];
        
        return view('frontend.dashboard',compact('data'),['jwtToken' => $access_token]);
    }

    public function chartDetails(){
        // { date: '2006', Present: 100, absent: 90 },
        $monthly_attendance_number = 1;
        $month_wise_expense_details = 1;
        try {
            $results = [];
            $company_id = auth()->user()->company_id;
            $total_emp = Employee::where('company_id', $company_id)
                ->where('deleted_at', null)
                ->count();
        
            for ($i = 0; $i < 30; $i++) {
                $currentDate = Carbon::now()->subDays($i)->toDateString();
                $isWeekend = Weekend::where(strtolower(Carbon::parse($currentDate)->format('l')), 1)
                                    ->where('company_id', $company_id)
                                    ->exists();
                $isHoliday = Holiday::where('date', $currentDate)->where('company_id',$company_id)->exists();
                if($isWeekend){
                    $results[$currentDate] = [
                        'total_attendance' => 0,
                        'total_absent' =>0,
                        'Weekend' => 1,
                        'Holiday' => 0
                    ];
                }elseif($isHoliday){
                    $results[$currentDate] = [
                        'total_attendance' => 0,
                        'total_absent' => 0,
                        'Weekend' => 0,
                        'Holiday' => 1
                    ];
                }else{
                    $total_attendance = Attendance::where('company_id', $company_id)
                    ->where('created_at', '>=', $currentDate . ' 00:00:00')
                    ->where('created_at', '<=', $currentDate . ' 23:59:59')
                    ->where('deleted_at', null)
                    ->count();
        
                    $total_on_leave = LeaveApplication::join('employees', 'leave_applications.emp_id', '=', 'employees.emp_id')
                        ->where('employees.company_id', $company_id)
                        ->where('leave_applications.status', 1)
                        ->whereJsonContains('leave_applications.dateArray', $currentDate)
                        ->count();
            
                    $total_absent = $total_emp - ($total_attendance + $total_on_leave);
            
                    $results[$currentDate] = [
                        'total_attendance' => $total_attendance,
                        'total_absent' => $total_absent,
                        'Weekend' => 0,
                        'Holiday' => 0
                    ];
                }
                
            }
        
            return response()->json([
                'message' => 'report',
                'data' => $results
            ], 200);
        } catch (QueryException $e) {
            // Log the exception or handle it as needed
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            return response()->json(['message' => 'Unexpected Error', 'error' => $e->getMessage()], 500);
        }
    }
}
