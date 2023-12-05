<?php

namespace App\Http\Controllers\frontendController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class attendanceController extends Controller
{
    public function attendanceType(){
        $access_token = session('access_token');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hrm.aamarpay.dev/api/attendance-type-details',
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
        return view('frontend.attendanceType',compact('dataArray'), ['jwtToken' => $access_token]);  
    }

    public function attendanceSetting(){
        $access_token = session('access_token');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hrm.aamarpay.dev/api/office-hour-list',
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
        return view('frontend.attendanceSetting',compact('dataArray'), ['jwtToken' => $access_token]);
    }

    public function attendanceList(){
        $access_token = session('access_token');
        
        //employeeList
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hrm.aamarpay.dev/api/employee-list',
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
        return view('frontend.attendanceList',compact('employee'), ['jwtToken' => $access_token]);        
    }

    public function editAttendance($id){
        $access_token = session('access_token');
        $company_id = session('company_id');
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
            
                return view('frontend.editAttendance',compact('attendance'));
        }else{
            return view('frontend.login');
        }
    }
    
}
