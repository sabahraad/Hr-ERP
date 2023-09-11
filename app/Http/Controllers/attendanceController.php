<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
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
        'IN' => 'boolean',
        'OUT' => 'boolean',
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
        dd($request->IN);
        $currentDate = Carbon::now()->toDateString(); // Gets current date
        $currentTime = Carbon::now()->toTimeString();
        $date=$request->IN;
        $carbonDate = Carbon::parse($date);  
        if ($carbonDate->isToday()) {
            // The provided date is today
            if ($carbonDate->gt(Carbon::now())) {
                // The provided date is in the future
            } elseif ($carbonDate->lt(Carbon::now())) {
                // The provided date is in the past
            } else {
                // The provided date is exactly now
            }
        } else {
            // The provided date is not today
        }
        if ($carbonDate->isToday()) {
            dd('ok');
        }
        dd($carbonDate,$currentTime);     
        $company_id= auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');
        

        $data = new Attendance();
        $data->IN = $request->IN;
        $data->OUT = $request->OUT;
        $data->reason = $request->reason;
        $data->emp_id = $emp_id;
        $data->company_id = $company_id;
        $data->edited = $request->edited;
        $data->editedBY = $request->editedBY;
        $data->id = $user_id;
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
