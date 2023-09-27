<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Weekend;
use Illuminate\Http\Request;

class weekendController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createWeekend(Request $request){

        $validator = Validator::make($request->all(), [
            'sunday' => 'required|boolean',
            'monday' => 'required|boolean',
            'tuesday' => 'required|boolean',
            'wednesday' => 'required|boolean',
            'thursday' => 'required|boolean',
            'friday' => 'required|boolean',
            'saturday' => 'required|boolean'
        ]);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;
        
        $weekendList = Weekend::where('company_id',$company_id)->first();
        if($weekendList){
            $weekendList->sunday = $request->sunday;
            $weekendList->monday = $request->monday;
            $weekendList->tuesday = $request->tuesday;
            $weekendList->wednesday = $request->wednesday;
            $weekendList->thursday = $request->thursday;
            $weekendList->friday = $request->friday;
            $weekendList->saturday = $request->saturday;
            $weekendList->save();
            return response()->json([
                'message'=>'Weekend Updated Successfully',
                'data'=>$request->all()
            ],200);
        }else{
            $data = new Weekend();
            $data->sunday = $request->sunday;
            $data->monday = $request->monday;
            $data->tuesday = $request->tuesday;
            $data->wednesday = $request->wednesday;
            $data->thursday = $request->thursday;
            $data->friday = $request->friday;
            $data->saturday = $request->saturday;
            $data->company_id = $company_id;
            $data->save();
    
            return response()->json([
                'message'=>'Weekend Added Successfully',
                'data'=>$data
            ],201);
        }
       
    }

    public function WeekendList(){
        $company_id= auth()->user()->company_id;
        $data = Weekend::where('company_id',$company_id)->get();
        return response()->json([
            'message'=> 'Weekend List',
            'data'=>$data
        ],200);
    }

    public function deleteWeekend(){
        $company_id = auth()->user()->company_id;
        Weekend::where('company_id',$company_id)->delete();
        return response()->json([
            'message' => 'Weekend deleted successfully'
        ]);
    }
}
