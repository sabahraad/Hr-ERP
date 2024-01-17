<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class visitController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createVisit(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'desc' => 'string',
            'visit_time' => 'required|date_format:Y-m-d H:i:s'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id = auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');

        $data = new Visit();
        $data->title = $request->title;
        $data->desc = $request->desc;
        $data->visit_time = $request->visit_time;
        $data->company_id = $company_id;
        $data->emp_id = $emp_id;

        $data->save();

        return response()->json([
            'message'=>'Visit Details Added',
            'data'=>$data
        ],201);
    }

    public function visitList(){
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');

        $data = Visit::where('emp_id',$emp_id)
                    ->where('status','pending')
                    ->orderBy('created_at','desc')
                    ->get();
        if(empty($data)){
            return response()->json([
                'message'=>'No data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Visit List',
                'data'=>$data
            ],200);
        }

    }

    public function completeVisit(Request $request){
        
    }

// latitude
// longtitude
// attachment
// status
// cancel_reason
// update_time

}
