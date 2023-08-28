<?php

namespace App\Http\Controllers;

use App\Models\leaveSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class leaveController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function leavesetting(Request $request){

        $validator = Validator::make($request->all(), [
            'annual_leave' => 'integer',
            'casual_leave' => 'integer',
            'maternity_leave' => 'integer',
            'medical_leave' => 'integer',
            'privilege_leave' => 'integer',
            'probationary_leave' => 'integer',
            'half_day_leave' => 'integer',
            'extended_leave' => 'integer',
            'paid_leave' => 'integer',
        ]);
        
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;
        $id = leaveSetting::where('company_id',$company_id)->value('leave_setting_id');
        $data=leaveSetting::find($id);
        if(!$data){
            $data= new leaveSetting();
        }
        
        $data->annual_leave = $request->annual_leave ?? $data->annual_leave ?? 0;
        $data->casual_leave = $request->casual_leave ?? $data->casual_leave ?? 0;
        $data->maternity_leave = $request->maternity_leave?? $data->maternity_leave?? 0;
        $data->medical_leave = $request->medical_leave?? $data->medical_leave?? 0;
        $data->privilege_leave = $request->privilege_leave?? $data->privilege_leave?? 0;
        $data->probationary_leave = $request->probationary_leave?? $data->probationary_leave?? 0;
        $data->half_day_leave = $request->half_day_leave?? $data->half_day_leave?? 0;
        $data->extended_leave = $request->extended_leave?? $data->extended_leave?? 0;
        $data->paid_leave = $request->paid_leave?? $data->paid_leave?? 0;
        $data->company_id = $company_id;
        $data->save();

        $data= leaveSetting::where('company_id',$company_id)->get();

        return response()->json([
            'message'=> 'Leave Setting Added',
            'data'=>$data
        ],201);
       
    }

    public function leavesettingList(){

        $company_id = auth()->user()->company_id;
        $data= leaveSetting::where('company_id',$company_id)->get();

        return response()->json([
            'message'=>'leave Setting List',
            'data'=>$data
        ],200);
    }


}
