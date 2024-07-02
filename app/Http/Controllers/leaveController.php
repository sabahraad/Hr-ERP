<?php

namespace App\Http\Controllers;
use App\Models\Approvers;
use App\Models\Attendance;
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
        if(($startDate <= $endDate) == true){
            while ($startDate <= $endDate) {
                $dateList[] = $startDate->toDateString();
                $startDate->addDay();
            }
        }else{
            return response()->json([
                'message'=> 'Leave End Date Can Not Be Smaller Then The Start Date'
            ],403);
        }
        
        // dd($dateList);
        //delete holiday from the list
        $holiday=Holiday::where('company_id',$company_id)->get();
        foreach($holiday as $raw){            
            $data=json_decode($raw->date);
            $remainingDates = array_diff($dateList, $data);
            $dateList = $remainingDates;
        }
        //get date and Day as key value pair
        foreach ($dateList as $date) {
            $carbonDate = Carbon::parse($date);
            $dayNames[] = $carbonDate->format('l'); // 'l' format gives the full day name
        }
        $keyValueDateList = array_combine( $dateList,$dayNames);

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

        //Same Date already leave apply check
        $statusArray = [0, 1]; // 0 = pending , 1 = approved 
        $alreadyAppliedLeave = LeaveApplication::where('emp_id', $emp_id)
                                            ->whereIn('status', $statusArray)
                                            ->where(function ($query) use ($dateArray) {
                                                foreach ($dateArray as $date) {
                                                    $query->orWhereJsonContains('dateArray', $date);
                                                }
                                            })
                                            ->pluck('dateArray')
                                            ->all();
        if(count($alreadyAppliedLeave) != 0){
            $alreadyAppliedLeaveDate = implode(', ', $alreadyAppliedLeave);
            return response()->json([
                'message'=>'You can not apply for this dates' .$alreadyAppliedLeaveDate . ' '.'Because You have already applied for those date',
                'data'=>$alreadyAppliedLeave
            ],403);
        }

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
        if($request->has('image')){
            $extension = $request->image->getClientOriginalExtension();
            if ($extension === 'pdf') {
                $pdfPath = $request->image->storeAs('pdfs', time() . '.' . $extension, 'public');
                $data->image = 'storage/'.$pdfPath;
            }
        
            if (in_array($extension, ['jpg','jpeg', 'png', 'gif', 'svg'])) {
                    $imagePath = $request->image->storeAs('images', time() . '.' . $extension, 'public');
                    $data->image = 'storage/'.$imagePath;
            }
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
        $response2 = leaveApplication::where('emp_id', $emp_id)->where('status',1)->get();
       
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
        $data = leaveApplication::where('emp_id', $id)
                ->join('leave_settings', 'leave_applications.leave_setting_id', '=', 'leave_settings.leave_setting_id')
                ->select('leave_applications.*', 'leave_settings.leave_type')
                ->orderBy('leave_applications.created_at', 'desc')
                ->get();
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
                    'leave_applications.reason',
                    'leave_settings.*',
                    'employees.*'
                )
                ->join(DB::raw('(SELECT leave_application_id, MIN(priority) AS min_priority FROM leave_approves WHERE status = 0 GROUP BY leave_application_id) AS temp_leave'), function ($join) {
                    $join->on('leave_approves.leave_application_id', '=', 'temp_leave.leave_application_id');
                })
                ->join('leave_applications', 'leave_approves.leave_application_id', '=', 'leave_applications.leave_application_id')
                ->join('leave_settings', 'leave_applications.leave_setting_id', '=', 'leave_settings.leave_setting_id')
                ->join('employees', 'leave_applications.emp_id', '=', 'employees.emp_id')
                ->where('leave_approves.status', '=', 0)
                ->where('approver_emp_id','=',$emp_id)
                ->whereColumn('leave_approves.priority', '=', 'temp_leave.min_priority')
                ->orderBy('leave_applications.created_at', 'desc')
                ->get();
       
        $result->each(function ($raw, $key) use ($emp_id, $result) {
            $approve = 1;
            if ($raw->emp_id == $emp_id) {
                LeaveApprove::where('leave_approves_id', $raw->leave_approves_id)->update(['status' => $approve]);
                // Remove the current $raw from $result
                $result->forget($key);
            }
        });
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
            $info = LeaveApprove::where('leave_approves_id', $leave_approves_id)->update(['status' => $approve]);
            $leave_application_id = LeaveApprove::where('leave_approves_id', $leave_approves_id)->value('leave_application_id');
            $data = LeaveApprove::where('leave_application_id', $leave_application_id)->get();
            $allStatusOne = $data->every(function ($item, $key) {
                return $item->status == 1;
            });
            if($allStatusOne){
                leaveApplication::where('leave_application_id', $leave_application_id)->update(['status' =>$approve]);
            }
            
            return response()->json([
                'message'=>'Leave Approved',
                'data'=>$info
            ],200);
        }elseif($status == $reject){
            LeaveApprove::where('leave_approves_id', $leave_approves_id)->update(['status' => $reject]);
            $leave_application_id = LeaveApprove::where('leave_approves_id', $leave_approves_id)->value('leave_application_id');
            $data = leaveApplication::where('leave_application_id', $leave_application_id)->update(['status' => $reject]);
            
            return response()->json([
                'message'=>'Leave Rejected',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message' => 'Leave Not Approved'
            ],400);
        }
    }

    public function allLeaveApplication(){
        $company_id = auth()->user()->company_id;
        $leaveApplications = LeaveApplication::with('employee')
                            ->whereHas('employee', function ($query) use ($company_id) {
                                $query->where('company_id', $company_id);
                            })
                            ->orderBy('created_at', 'desc')
                            ->get();

        if($leaveApplications){
            return response()->json([
                'message'=>'All Leave Application',
                'data'=>$leaveApplications
            ],200);
        }else{
            return response()->json([
                'message'=>'No data found'
            ],404);
        }
        
    } 

    public function monthWiseOffDayList(Request $request){
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
       
        $company_id = auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id',$user_id)->value('emp_id');
        $daysInMonth = Carbon::createFromDate($request->year, $request->month, 1)->daysInMonth;
        $dateList = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Weekend check
            $currentDate = Carbon::createFromDate($request->year, $request->month, $day);
            $date= "$request->year-$request->month-$day";
            
            $isWeekend = Weekend::where(strtolower($currentDate->format('l')), 1)->where('company_id',$company_id)->exists();
            // hoilday check
            $currentDateString = $currentDate->toDateString();
            $isHoliday = Holiday::where('company_id', $company_id)
                ->whereJsonContains('date', $currentDateString)
                ->exists();
            // dd($isHoliday);
            // $isHoliday = Holiday::where('date', $currentDate->toDateString())->where('company_id',$company_id)->exists();

            // leave check
            $isLeave = leaveApplication::where('dateArray', 'like', "%{$currentDate->toDateString()}%")->where('status',1)->where('emp_id',$emp_id)->exists();
            
            if ($isWeekend) {
                $code = 2; // Weekend
            } elseif ($isHoliday) {
                $code = 3; // Holiday
            } elseif ($isLeave) {
                $code = 4; // Leave
            } else {
                $code = 1; // Working day
            }
        
            $dateList[$date] = $code;
        }
        
        if($dateList){
            return response()->json([
                'message'=>'Monthwise Data',
                'data'=>$dateList
            ],200);
        }else{
            return response()->json([
                'message'=>'Somthing Went Wrong'
            ],404);
        }
        
    }

    public function monthWiseReport(Request $request){

        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
       
        $company_id = auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id',$user_id)->value('emp_id');
        $daysInMonth = Carbon::createFromDate($request->year, $request->month, 1)->daysInMonth;
        $dateList = [];
        $attendanceList=[];
        $data = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $currentDate = Carbon::createFromDate($request->year, $request->month, $day);
            $date= "$request->year-$request->month-$day";
            // dd($date);
            $dateToCheck = Carbon::parse($date);
            $dateValue = $currentDate->toDateString();
            // Weekend check
            $isWeekend = Weekend::where(strtolower($currentDate->format('l')), 1)->where('company_id',$company_id)->exists();

            // hoilday check
            $currentDateString = $currentDate->toDateString();
            $isHoliday = Holiday::where('company_id', $company_id)
                ->whereJsonContains('date', $currentDateString)
                ->exists();
            // $isHoliday = Holiday::where('date', $currentDate->toDateString())->where('company_id',$company_id)->exists();

            // leave check
            $isLeave = leaveApplication::where('dateArray', 'like', "%{$currentDate->toDateString()}%")->where('status',1)->where('emp_id',$emp_id)->exists();
            
            if ($isWeekend) {
            // Weekend
                $attendanceList['late'] = false;
                $attendanceList['present'] = false;
                $attendanceList['Absent'] = false;
                $attendanceList['leave'] = false;
                $attendanceList['weekend'] = true;
                $attendanceList['holiday'] = false;
                $attendanceList['workingDay'] = false;
            } elseif ($isHoliday) {
             // Holiday
                $attendanceList['late'] = false;
                $attendanceList['present'] = false;
                $attendanceList['Absent'] = false;
                $attendanceList['leave'] = false;
                $attendanceList['weekend'] = false;
                $attendanceList['holiday'] = true;
                $attendanceList['workingDay'] = false;

            } elseif ($isLeave) {
            // Leave
                $attendanceList['late'] = false;
                $attendanceList['present'] = false;
                $attendanceList['Absent'] = false;
                $attendanceList['leave'] = true;
                $attendanceList['weekend'] = false;
                $attendanceList['holiday'] = false;
                $attendanceList['workingDay'] = true;

            } else {
             // Working day
                if($dateToCheck->isFuture()){
                    $attendanceList['late'] = false;
                    $attendanceList['present'] = false;
                    $attendanceList['Absent'] = false;
                    $attendanceList['leave'] = false;
                    $attendanceList['weekend'] = false;
                    $attendanceList['holiday'] = false;
                    $attendanceList['workingDay'] = true;
                }else{
                    //attendance list
                    $attendanceDetails = Attendance::whereDate('created_at', $date)->where('emp_id',$emp_id)->first();
                    // dd($attendanceDetails);
                    if($attendanceDetails == null){
                        $attendanceList['late'] = false;
                        $attendanceList['present'] = false;
                        $attendanceList['Absent'] = true;
                        $attendanceList['leave'] = false;
                        $attendanceList['weekend'] = false;
                        $attendanceList['holiday'] = false;
                        $attendanceList['workingDay'] = true;
                    }elseif($attendanceDetails->INstatus == 2){
                        $attendanceList['late'] = true;
                        $attendanceList['present'] = true;
                        $attendanceList['Absent'] = false;
                        $attendanceList['leave'] = false;
                        $attendanceList['weekend'] = false;
                        $attendanceList['holiday'] = false;
                        $attendanceList['workingDay'] = true;
                    }else{
                        $attendanceList['late'] = false;
                        $attendanceList['present'] = true;
                        $attendanceList['Absent'] = false;
                        $attendanceList['leave'] = false;
                        $attendanceList['weekend'] = false;
                        $attendanceList['holiday'] = false;
                        $attendanceList['workingDay'] = true;
                    }
                }
                
            }
                
            $data[$dateValue] = $attendanceList;
        }
        // dd($data,$dateList);
        $absentCount = 0;
        $LateCount = 0;
        $workingDays = 0;
        $weekends = 0;
        $hoildays = 0;
        $leaves = 0;
        $present = 0;
        foreach ($data as $date => $details) {
            if ($details['Absent']) {
                $absentCount++;
            }
            if ($details['late']) {
                $LateCount++;
            }
            if ($details['workingDay']) {
                $workingDays++;
            }
            if ($details['weekend']) {
                $weekends++;
            }
            if ($details['holiday']) {
                $hoildays++;
            }
            if ($details['leave']) {
                $leaves++;
            }
            if ($details['present']) {
                $present++;
            }
        }
        
        
        return response()->json([
            'message'=> 'Monthly Report',
            'Total Working Day Count' => $workingDays,
            'Total Weekend Count' => $weekends,
            'Total Holiday Count' => $hoildays,
            'Total Leave Count' => $leaves,
            'Total Absent Count' => $absentCount,
            'Total Late Count' => $LateCount,
            'Total Present Count' => $present,
            'data' => $data
        ],200);
    }

    public function deleteLeaveApplication($id){
        leaveApplication::destroy($id);
        return response()->json([

        ],204);
    }

    public function leaveApprovedByHR(Request $request){

        $leave = leaveApplication::find($request->leaveApplicationId);
        $leave->status = $request->status;
        $leave->save();

        return response()->json([
            'message' => 'Leave Approved Successfully',
            'data'=>$request->status
        ],200);

    }

    public function leaveApplicationDetails($id){
        $data = leaveApplication::where('leave_application_id',$id)
                ->join('employees','employees.emp_id','=','leave_applications.emp_id')
                ->get(['leave_applications.*','employees.name']);
        return response()->json([
            'message' => 'Leave Application Details',
            'data'=>$data
        ],200);
    }

}
