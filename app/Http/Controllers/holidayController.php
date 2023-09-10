<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class holidayController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createHoliday(Request $request){

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'reason' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $company_id= auth()->user()->company_id;

        $data=new Holiday();
        $data->date = $request->date;
        $data->reason = $request->reason;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=> 'Holiday Created Successfully',
            'data'=>$data
        ],201);
    }

    public function HolidayList(){

        $company_id= auth()->user()->company_id;
        $data=Holiday::where('company_id',$company_id)->get();
        return response()->json([
            'message'=>'Holiday List',
            'data'=>$data
        ],200);

    }

    public function updateHoliday(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'reason' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $company_id= auth()->user()->company_id;

        $data=Holiday::find($id);
        $data->date = $request->date;
        $data->reason = $request->reason;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=> 'Holiday Updated Successfully',
            'data'=>$data
        ],201);

    }

    public function deleteHoliday($id){
        Holiday::where('holidays_id',$id)->delete();        
        return response()->json([
            'message' => 'Holiday deleted successfully'
        ]);
    }

}
