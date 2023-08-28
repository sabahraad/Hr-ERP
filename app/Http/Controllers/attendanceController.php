<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class attendanceController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    protected $validationRules = [
        'IN' => 'date_format:Y-m-d H:i:s',
        'OUT' => 'date_format:Y-m-d H:i:s',
        'reason' => 'string', 
        'edited' => 'boolean', 
        'editedBY' => 'string'
    ];

    public function createAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $company_id= auth()->user()->company_id;
        $user_id = auth()->user()->id;

        $data = new Attendance();
        $data->IN = $request->IN;
        $data->OUT = $request->OUT;
        $data->reason = $request->reason;
        $data->id = $user_id;
        $data->company_id = $company_id;
        $data->edited = $request->edited;
        $data->editedBY = $request->editedBY;
        $data->save();

        return response()->json([
            'message' => 'Attendance Accepted Successfully',
            'data' => $request->all()
        ], 201);

    }

    public function showattendance(){

        $user_id = auth()->user()->id;
        $data= Attendance::where('id',$user_id)->get();

        if (count($data) === 0) {
            return response()->json([
                'message' => 'No Attendance Found',
            ],Response::HTTP_NOT_FOUND);

        }else{

            return response()->json([
                'message' => 'Attendance List',
                'data' => $data,
    
            ],Response::HTTP_OK);
        }
    }

    public function updateattendance(Request $request,$id){

        $validator = Validator::make($request->all(), $this->validationRules);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $data = Attendance::find($id);
        if(!$data){
            return response()->json([
                'message' => 'No Attendance Found'
            ],Response::HTTP_NOT_FOUND);
        }
        $company_id= auth()->user()->company_id;
        $user_id = auth()->user()->id;

        $data->IN = $request->IN;
        $data->OUT = $request->OUT;
        $data->reason = $request->reason;
        $data->id = $user_id;
        $data->company_id = $company_id;
        $data->edited = $request->edited;
        $data->editedBY = $request->editedBY;
        $data->save();

        return response()->json([
            'message' => 'Attendance Updated Successfully',
            'data' => $request->all()
        ], 201);

    }

}
