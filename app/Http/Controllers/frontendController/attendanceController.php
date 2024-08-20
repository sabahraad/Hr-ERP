<?php

namespace App\Http\Controllers\frontendController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class attendanceController extends Controller
{
    protected $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = BaseUrl::get();
    }

    public function attendanceType(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/attendance-type-details',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $access_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $dataArray = json_decode($response,true);
        return view('frontend.attendanceType',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);  
    }

    public function attendanceSetting(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/office-hour-list',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $access_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $dataArray = json_decode($response,true);
        // dd($dataArray);
        return view('frontend.attendanceSetting',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

    public function attendanceList(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        //employeeList
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/all-employee-list',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $access_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $employee = json_decode($response,true);
        return view('frontend.attendanceList',compact('employee'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);        
    }

    public function editAttendance($id){
        $access_token = session('access_token');
        $company_id = session('company_id');
        $baseUrl = $this->baseUrl;
        if($access_token){
            $attendance = Attendance::select(
                            'attendances.*',
                            'employees.name as employee_name',
                            'editedByEmployee.name as edited_by_name'
                        )
                        ->join('employees', 'attendances.emp_id', '=', 'employees.emp_id')
                        ->leftJoin('employees as editedByEmployee', 'attendances.editedBY', '=', 'editedByEmployee.emp_id')
                        ->where('attendances.attendance_id', '=', $id)
                        ->where('attendances.company_id', $company_id)
                        ->get();
            
                return view('frontend.editAttendance',compact('attendance'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
        }else{
            return view('frontend.login');
        }
    }

    public function individualAttendanceReport($id,$startDate,$endDate){
        $result = Attendance::select(
                                'attendances.*',
                                'employees.name as employee_name',
                                'editedByEmployee.name as edited_by_name'
                            )
                            ->join('employees', 'attendances.emp_id', '=', 'employees.emp_id')
                            ->leftJoin('employees as editedByEmployee', 'attendances.editedBY', '=', 'editedByEmployee.emp_id')
                            ->whereDate('attendances.created_at', '>=', $startDate)
                            ->whereDate('attendances.created_at', '<=', $endDate)
                            ->where('attendances.emp_id', $id)
                            ->orderBy('attendances.emp_id')
                            ->get();
        return view('frontend.individualAttendanceReport',compact('result'));
    }

    public function showMap($id){
        
        $attendance = Attendance::find($id);
        return view('map', [
            'checkIN_latitude' => $attendance->checkIN_latitude,
            'checkIN_longitude' => $attendance->checkIN_longitude,
            'checkOUT_latitude' => $attendance->checkOUT_latitude,
            'checkOUT_longitude' => $attendance->checkOUT_longitude,
        ]);
    }

}
