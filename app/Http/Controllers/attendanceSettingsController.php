<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class attendanceSettingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createAttendanceSetting(Request $request){

        $validator = Validator::make($request->all(), [
            'office_hour_type' => 'required|string',
            'office_hour' => 'required|date_format:H:i:s',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
            'grace_time' => 'required|date_format:H:i:s'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;
        $attendanceSetting = AttendanceSetting::where('company_id',$company_id)->first();
        if($attendanceSetting){
            $attendanceSetting->office_hour_type = $request->office_hour_type;
            $attendanceSetting->office_hour = $request->office_hour;
            $attendanceSetting->start_time = $request->start_time;
            $attendanceSetting->end_time = $request->end_time;
            $attendanceSetting->grace_time = $request->grace_time;
            $attendanceSetting->save();
            return response()->json([
                'message'=>'Attendance Settings Updated Successfully',
                'data'=>$attendanceSetting
            ],200);
        }else{
            $data=new AttendanceSetting();
            $data->office_hour_type = $request->office_hour_type;
            $data->office_hour = $request->office_hour;
            $data->start_time = $request->start_time;
            $data->end_time = $request->end_time;
            $data->grace_time = $request->grace_time;
            $data->company_id = $company_id;
            $data->save();
    
            return response()->json([
                'message'=>'Attendance Settings Added Successfully',
                'data'=>$data
            ],201);
        }  
    }

    public function AttendanceSettingList(){
        $company_id= auth()->user()->company_id;
        $data=AttendanceSetting::where('company_id',$company_id)->get();
        return response()->json([
            'message'=>'Attendance Settings List',
            'data'=>$data
        ],200);
    }

    public function deleteAttendanceSetting(){
        $company_id= auth()->user()->company_id;
        AttendanceSetting::where('company_id',$company_id)->delete();        
        return response()->json([
            'message' => 'Attendance Settings deleted successfully'
        ]);
    }
    
}
