<?php

namespace App\Http\Controllers\Director;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\leaveApplication;
use App\Models\Requisition;
use Carbon\Carbon;
use App\Utils\BaseUrl;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmailJob;

class dashboardController extends Controller
{
    public function dashboard(){
        $access_token = session('access_token');
        $company_id = session('company_id');
        $baseUrl = BaseUrl::get();
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
                                            ->whereJsonContains('leave_applications.dateArray', $today)
                                            ->count();
        $total_absent = $total_emp - ($total_attendance + $total_on_leave);
        $data = [
            'total_emp' => $total_emp,
            'total_attendance' => $total_attendance,
            'total_on_leave' => $total_on_leave,
            'total_absent' => $total_absent
        ];
        return view('Director.dashboard',compact('data'),['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

    public function requisitionList(){
        $company_id= session('company_id');
        $user_id = session('id');
        $emp_id = Employee::where('id', $user_id)->value('emp_id');
        $data = Requisition::where('requisitions.company_id', $company_id)
        ->join('requisition_categories', 'requisition_categories.requisition_categories_id', '=', 'requisitions.requisition_categories_id')
        ->join('employees', 'employees.emp_id', '=', 'requisitions.emp_id')
        ->select('requisitions.*', 'requisition_categories.category_name', 'employees.name as employee_name')
        ->get();
        return view('Director.requisitionList',compact('data'));
    }

    public function presentEmployeeList(){
        $company_id = session('company_id');
        $currentDate = date('Y-m-d');
        $data = Attendance::select(
                        'attendances.*',
                        'employees.name as employee_name',
                        'editedByEmployee.name as edited_by_name'
                    )
                    ->join('employees', 'attendances.emp_id', '=', 'employees.emp_id')
                    ->leftJoin('employees as editedByEmployee', 'attendances.editedBY', '=', 'editedByEmployee.emp_id')
                    ->whereDate('attendances.created_at', '>=', $currentDate)
                    ->whereDate('attendances.created_at', '<=', $currentDate)
                    ->where('attendances.company_id', $company_id)
                    ->orderBy('attendances.emp_id')
                    ->get();
        return view('frontend.presentEmployeeList',compact('data'));
    }

    public function absentEmployeeList(){
        $company_id = session('company_id');
        $currentDate = date('Y-m-d');
        $data = Employee::where('company_id', $company_id)
            ->whereNotExists(function ($query) use ($currentDate) {
                $query->select(DB::raw(1))
                    ->from('attendances')
                    ->whereRaw('attendances.emp_id = employees.emp_id')
                    ->whereDate('attendances.created_at', $currentDate);
            })
            ->pluck('emp_id','name');

        return view('frontend.absentEmployeeList',compact('data'));
    }
}
