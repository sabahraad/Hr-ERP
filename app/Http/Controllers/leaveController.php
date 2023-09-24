<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\leaveApplication;
use App\Models\Weekend;
use App\Models\Holiday;
use App\Models\leaveSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class leaveController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addleavesetting(Request $request){

        $company_id= auth()->user()->company_id;
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer',
            // 'leave_type' => 'required|string|max:20|unique:leave_settings',
            'leave_type' => [
                'required',
                'string',
                'max:20',
                Rule::unique('leave_settings', 'leave_type')->where(function ($query) use ($company_id) {
                    return $query->where('company_id', $company_id);
                }),],
            'status' => 'required|boolean'
        ]);
        
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        
        $data= new leaveSetting();
        $data->days = $request->days;
        $data->leave_type = $request->leave_type;
        $data->status = $request->status;
        $data->company_id = $company_id;
        $data->save();

        // $data= leaveSetting::where('leave_type',$request->leave_type)->get();

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

    public function updateleavesetting(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'days' => 'required|integer',
            'leave_type' => 'required|string|max:20',
            'status' => 'required|boolean'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;
        $data = leaveSetting::find($id);
        if(!$data){
            return response()->json([
                'message' => 'Leave Type Not Found'
            ],404);
        }
        $data->days = $request->days;
        $data->leave_type = $request->leave_type;
        $data->status = $request->status;
        $data->company_id = $company_id;
        $data->save();

        $data= leaveSetting::where('company_id',$company_id)->get();

        return response()->json([
            'message'=> 'Leave Setting Updated',
            'data'=>$data
        ],200);
    }

    public function deleteleavesetting($id){
        leaveSetting::where('leave_setting_id',$id)->delete();        
        return response()->json([
            'message' => 'Leave Type deleted successfully'
        ]);
    }

    public function createLeaveApplications(Request $request){

        $validator = Validator::make($request->all(), [
            'leave_setting_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'reason' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');
        $company_id= auth()->user()->company_id;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        //leave Date List
        while ($startDate <= $endDate) {
            $dateList[] = $startDate->toDateString();
            $startDate->addDay();
        }
        //delete holiday from the list
        $holiday=Holiday::where('company_id',$company_id)->get();
        foreach($holiday as $raw){
            $data=$raw->date;
            $dateListWithoutHoliday = array_filter($dateList, function($value) use ($data) {
                return $value !== $data ;
            });
            $dateList = $dateListWithoutHoliday;
        }
        //get date and Day as key value pair
        foreach ($dateListWithoutHoliday as $date) {
            $carbonDate = Carbon::parse($date);
            $dayNames[] = $carbonDate->format('l'); // 'l' format gives the full day name
        }
        $keyValueDateList = array_combine( $dateListWithoutHoliday,$dayNames);
        
        $weekend=Weekend::where('company_id',$company_id)->first();
        $data=$weekend->getAttributes();
        $weekendDayNames = array_keys(array_filter($data, function($value) {
            return $value === 1;
        }));
        $weekendDayNames = array_diff($weekendDayNames, ["company_id"]);
        
        foreach($weekendDayNames as $raw){
            $dateListWithoutWeekend = array_filter($keyValueDateList, function($value) use ($raw) {
                return $value !== $raw ;
            });
            $keyValueDateList = $dateListWithoutWeekend ;
        }

        $dateArray=array_keys($dateListWithoutWeekend );
        $jsonData = json_encode($dateArray);

        $count=count($dateArray);

        $data = new leaveApplication();

        if($request->hasFile('image')){
            $imageName =  time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
            $data->image = $imagePath;
        }
        
        $data->emp_id = $emp_id;
        $data->leave_setting_id = $request->leave_setting_id;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->dateArray = $jsonData;
        $data->count = $count;
        $data->status = $request->status;
        $data->reason = $request->reason;
        $data->approvel_date = $request->approvel_date;
        $data->approval_name = $request->approval_name;
        $data->save();

        return response()->json([
            'message' => 'Your Leave Application Submitted Successfully',
            'data'=> $data
        ],201);
    }

}
