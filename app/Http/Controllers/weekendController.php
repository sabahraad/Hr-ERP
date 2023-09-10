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

    public function WeekendList(){
        $company_id= auth()->user()->company_id;
        $data = Weekend::where('company_id',$company_id)->get();
        // $selectedDays = array_filter($data[1]->getAttributes(), function($value) {
        //     return $value == 1;
        // });
        return response()->json([
            'message'=> 'Weekend List',
            'data'=>$data
        ],200);
    }

    public function updateWeekend(Request $request,$id){

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

        $data=Weekend::find($id);
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
            'message'=>'Weekend Updated Successfully',
            'data'=>$data
        ],200);
    }

    public function deleteWeekend($id){
        Weekend::where('weekends_id',$id)->delete();        
        return response()->json([
            'message' => 'Weekend deleted successfully'
        ]);
    }
}
