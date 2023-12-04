<?php

namespace App\Http\Controllers;
use App\Models\Approvers;
use App\Models\LeaveApprove;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\leaveApplication;
use App\Models\Weekend;
use App\Models\Holiday;
use App\Models\leaveSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class leaveController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addleavetype(Request $request){

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
            'message'=> 'Leave Type Added',
            'data'=>$data
        ],201);
       
    }

    public function leaveTypeList(){

        $company_id = auth()->user()->company_id;
        $data= leaveSetting::where('company_id',$company_id)->get();

        return response()->json([
            'message'=>'leave Type List',
            'data'=>$data
        ],200);
    }

    public function updateleavetype(Request $request,$id){
        $company_id= auth()->user()->company_id;
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer',
            'leave_type' => 'required|string|max:20|unique:leave_settings,leave_type,' . $id . ',leave_setting_id,company_id,' . $company_id,
            'status' => 'required|boolean'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

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

        // $data= leaveSetting::where('company_id',$company_id)->get();

        return response()->json([
            'message'=> 'Leave Type details Updated',
            'data'=>$data
        ],200);
    }

    public function deleteleaveType($id){
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
        $leaveTaken = leaveApplication::where('leave_setting_id',$request->leave_setting_id)
                             ->where('emp_id',$emp_id)
                             ->get();
        $leaveTakenCount = $leaveTaken->sum('count');
        $leaveDayCount = leaveSetting::where('leave_setting_id',$request->leave_setting_id)->value('days');
        $availableLeaveCount = $leaveDayCount - $leaveTakenCount;
        // dd($leaveTakenCount,$leaveDayCount,$availableLeaveCount); 
        if($count>$availableLeaveCount){
            return response()->json([
                'message' => 'You can apply for '.$availableLeaveCount.' days',
            ],403);
        }

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
        $data->status = 0;
        $data->reason = $request->reason;
        $data->approvel_date = $request->approvel_date;
        $data->approval_name = $request->approval_name;
        if($data->save()){
            $dept_id = Employee::where('emp_id',$data->emp_id)->value('dept_id');
            $approvers = Approvers::where('deptId',$dept_id)->get();
            $leave_application_id = $data->leave_application_id;
            // dd($approvers);
            foreach($approvers as $value){
                $leaveApproverList = new LeaveApprove();
                $leaveApproverList->dept_id = $dept_id;
                $leaveApproverList->leave_application_id = $leave_application_id;
                $leaveApproverList->approver_emp_id = $value->emp_id;
                $leaveApproverList->approver_name = $value->approver_name;
                $leaveApproverList->status = 0;
                $leaveApproverList->priority = $value->priority;
                $leaveApproverList->save();
            }
        }

        return response()->json([
            'message' => 'Your Leave Application Submitted Successfully',
            'data'=> $data
        ],201);
    }

    public function availableLeaveListforEmployee(){
        $company_id = auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');
        $response1= leaveSetting::where('company_id',$company_id)->get();
        $response2 = leaveApplication::where('emp_id', $emp_id)->get();
       
        $data1 = json_decode($response1, true);
        $data2 = json_decode($response2, true);

        $results = [];

        foreach ($data1 as $item1) {
            $leave_setting_id1 = $item1['leave_setting_id'];
            $days1 = $item1['days'];
            foreach ($data2 as $item2) {
                $leave_setting_id2 = $item2['leave_setting_id'];
                $count = $item2['count'];

                if ($leave_setting_id1 === $leave_setting_id2) {
                    $item1['days'] = $days1 - $count;
                    $days1 = $item1['days'];
                }
            }
            $results[] = $item1;

        }


        return response()->json([
            'message' => 'Avaiable Leave List',
            'data'=>$results
        ],200);

    }

    public function leaveApplicationsList($id){
        $data = leaveApplication::where('emp_id', $id)->get();
        if(!$data){
            return response()->json([
                'message'=> 'You have not requested any leave yet'
            ],200);
        }
        return response()->json([
            'message' => 'Leave Application List',
            'data' =>$data
        ],200);
    }

    public function leaveApproveList(){
        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id', $user_id)->value('emp_id');
        $result = DB::table('leave_approves')
                ->select(
                    'leave_approves.leave_approves_id',
                    'leave_approves.leave_application_id',
                    'leave_approves.dept_id',
                    'leave_approves.approver_emp_id',
                    'leave_approves.approver_name',
                    'leave_approves.status',
                    'leave_approves.created_at',
                    'leave_approves.updated_at',
                    'leave_approves.priority',
                    'temp_leave.min_priority',
                    'leave_applications.status AS application_status',
                    'leave_applications.start_date',
                    'leave_applications.end_date',
                    'leave_applications.dateArray',
                    'leave_applications.count AS total_leave_day_count',
                    'leave_applications.reason'
                )
                ->join(DB::raw('(SELECT leave_application_id, MIN(priority) AS min_priority FROM leave_approves WHERE status = 0 GROUP BY leave_application_id) AS temp_leave'), function ($join) {
                    $join->on('leave_approves.leave_application_id', '=', 'temp_leave.leave_application_id');
                })
                ->join('leave_applications', 'leave_approves.leave_application_id', '=', 'leave_applications.leave_application_id')
                ->where('leave_approves.status', '=', 0)
                ->where('approver_emp_id','=',$emp_id)
                ->whereColumn('leave_approves.priority', '=', 'temp_leave.min_priority')
                ->get();
        // dd($result);
        if(!$result){
            return response()->json([
                'message' => 'No Request for approvel yet',
                'data'=>$result
            ],404);
        }
        return response()->json([
            'message' => 'Pending leave Approve List',
            'data'=>$result
        ],200);

    }

    public function approveLeave(Request $request){
        $leave_approves_id = $request->leave_approves_id;
        $status = $request->status;
        $approve = 1;
        $reject = 2;
        $pending = 0;
        if($status == $approve){
            LeaveApprove::where('leave_approves_id', $leave_approves_id)->update(['status' => $approve]);
            $leave_application_id = LeaveApprove::where('leave_approves_id', $leave_approves_id)->value('leave_application_id');
            $data = LeaveApprove::where('leave_application_id', $leave_application_id)->get();
            $status = $data->contains(2) ? 2 : ($data->contains(0) ? 0 : 1);
            if($status == $approve || $status == $reject){
                leaveApplication::where('leave_application_id', $leave_application_id)->update(['status' => $status]);
            }
            return response()->json([
                'message'=>'Leave Approved'
            ],200);
        }elseif($status == $reject){
            LeaveApprove::where('leave_approves_id', $leave_approves_id)->update(['status' => $reject]);
            $leave_application_id = LeaveApprove::where('leave_approves_id', $leave_approves_id)->value('leave_application_id');
            $data = LeaveApprove::where('leave_application_id', $leave_application_id)->get();
            $status = $data->contains(2) ? 2 : ($data->contains(0) ? 0 : 1);
            if($status == $approve || $status == $reject){
                leaveApplication::where('leave_application_id', $leave_application_id)->update(['status' => $status]);
            }
            return response()->json([
                'message'=>'Leave Rejected'
            ],200);
        }else{
            return response()->json([
                'message' => 'Leave Not Approved'
            ],400);
        }
    }

}
