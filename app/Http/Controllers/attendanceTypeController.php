<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use App\Models\AttendanceType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class attendanceTypeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createAttendanceType(Request $request){
        $validator = Validator::make($request->all(), [
            'location_based' => 'required|boolean',
            'remote' => 'required|boolean',
            'wifi_based' => 'required|boolean',
            'iot_based' => 'required|boolean'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;

        $data=new AttendanceType();
        $data->location_based = $request->location_based;
        $data->remote = $request->remote;
        $data->wifi_based = $request->wifi_based;
        $data->iot_based = $request->iot_based;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=>'Attendance Type Added Successfully',
            'data'=>$data
        ],201);
    }

    public function AttendanceTypeList(){
        $company_id= auth()->user()->company_id;
        $data=AttendanceType::where('company_id',$company_id)->get();
        return response()->json([
            'message'=>'Attendance Type Details',
            'data'=>$data
        ],200);
    }

    public function updateAttendanceType(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'location_based' => 'required|boolean',
            'remote' => 'required|boolean',
            'wifi_based' => 'required|boolean',
            'iot_based' => 'required|boolean'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;

        $data=AttendanceType::find($id);
        $data->location_based = $request->location_based;
        $data->remote = $request->remote;
        $data->wifi_based = $request->wifi_based;
        $data->iot_based = $request->iot_based;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=>'Attendance Type Updated Successfully',
            'data'=>$data
        ],200);

    }

    public function deleteAttendanceType($id){
        AttendanceType::where('attendance_types_id',$id)->delete();        
        return response()->json([
            'message' => 'Attendance Type deleted successfully'
        ]);
    }
}
