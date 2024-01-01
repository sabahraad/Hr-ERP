<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\AttendanceType;
use App\Models\Employee;
use App\Models\IP;
use App\Models\officeLocation;
use App\Models\User;
use App\Models\Weekend;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use DateTime;

class attendanceController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    protected $validationRules = [
        'latitude' => 'required|numeric ',
        'longitude' => 'required|numeric    ',
        'action' => 'required|integer',
        'reason' => 'string', 
        'edit_reason' => 'string', 
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

        $currentTime = Carbon::now()->toTimeString();
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');
        $company_id= auth()->user()->company_id;
        $checkIN = 1;
        $checkOut = 2;

        //Weekend check
        $Weekend = Weekend::where('company_id',$company_id)->first();
        $data=$Weekend->getAttributes();
        $Weekend = array_keys(array_filter($data, function($value) {
            return $value === 1;
        }));
        $currentDayName = Carbon::now()->format('l');

        if (in_array($currentDayName, $Weekend)) {
            return response()->json([
                'message' => 'Attendance cannot be submitted on weekends.'
            ],422);
        }
        
        //Attendance check
        $attendanceType = AttendanceType::where('company_id',$company_id)->first();
        $data=$attendanceType->getAttributes();
        $attendanceType = array_keys(array_filter($data, function($value) {
            return $value === 1;
        }));
        if(in_array("location_based",$attendanceType)){
            $officeLocation = officeLocation::where('company_id',$company_id)->where('status',1)->first();
            // Radius of the Earth in km
            $earthRadius = 6371;
           // Convert latitude and longitude from degrees to radians
            $userLat = deg2rad($request->latitude);
            $userLong = deg2rad($request->longitude);
            $targetLat = deg2rad($officeLocation->latitude);
            $targetLong = deg2rad($officeLocation->longitude);
            $radius = $officeLocation->radius;
            // Calculate the change in coordinates
            $latDiff = $targetLat - $userLat;
            $longDiff = $targetLong - $userLong;
            // Haversine formula
            $a = sin($latDiff/2) * sin($latDiff/2) + cos($userLat) * cos($targetLat) * sin($longDiff/2) * sin($longDiff/2);
            $c = 2 * atan2(sqrt($a), sqrt(1-$a));
            $distance = $earthRadius * $c;
            
            if($distance <= $radius){
                $takePresent = 1;
            }else{
                return response()->json([
                    'message'=> 'You are presently away from the office premises.'
                ],403);
            }
        }
        if(in_array("wifi_based",$attendanceType)){
            $ip = IP::where('company_id',$company_id)->value('ip');
            $ipList=json_decode($ip);
            if (in_array($request->ip(), $ipList)) {
                $takePresent = 1;
            }else{
                return response()->json([
                    'message'=> 'Please connect to the office network'
                ],403);
            }
        }
        if(in_array("remote",$attendanceType)){
            $takePresent = 1;
        }

        if($request->action == $checkIN){
            //Current date attendance check
            $attendance = Attendance::where('emp_id',$emp_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->value('IN');
            if($attendance == 1){
                return response()->json([
                    'message'=> 'Your Are Already Checked IN'
                ],400);
            }
            //Late or on time check
            $officeTime = Carbon::createFromFormat('H:i:s', AttendanceSetting::where('company_id', $company_id)->value('start_time'));
            $graceTime = Carbon::createFromFormat('H:i:s', AttendanceSetting::where('company_id', $company_id)->value('grace_time'));
            $totalTime = $officeTime->addHours($graceTime->hour)
                        ->addMinutes($graceTime->minute)
                        ->addSeconds($graceTime->second);
            $totalTime = $totalTime->format('H:i:s');
            if($totalTime >= $currentTime){
                $status = 1;
            }else{
                $status = 2;
            }
            
            if($takePresent == 1){
                $data = new Attendance();
                $data->IN = 1;
                $data->OUT = $request->OUT;
                $data->lateINreason = $request->reason;
                $data->INstatus = $status;
                $data->emp_id = $emp_id;
                $data->company_id = $company_id;
                $data->edit_reason = $request->edit_reason;
                $data->editedBY = $request->editedBY;
                $data->id = $user_id;
                $data->save();

                return response()->json([
                    'message' => 'Attendance Accepted Successfully',
                    'data' => $data
                ], 201);
            }                    
        }
        if($request->action == $checkOut){
            
            $onTime = 1;
            $earlyLeave = 2;
            //Current date attendance check
            $OUT = Attendance::where('emp_id',$emp_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->value('OUT');
            if($OUT == $onTime || $OUT == $earlyLeave){
                return response()->json([
                    'message'=> 'Your Are Already Checked OUT'
                ],400);
            }
            $attendance = Attendance::where('emp_id',$emp_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->value('IN');
            if($attendance == 1){
                $endTime = Carbon::createFromFormat('H:i:s', AttendanceSetting::where('company_id', $company_id)->value('end_time'));
                $endTime = $endTime->format('H:i:s');
                if($endTime <= $currentTime){
                    $data= Attendance::whereDate('created_at', '=', Carbon::today()->toDateString())->where('emp_id',$emp_id)->first();
                    $data->OUT = $onTime;
                    $data->OUTstatus = $onTime;
                    $data->save();
                    return response()->json([
                        'message' => 'Successfully Checked Out for Today',
                        'data'=> $data
                    ],201);
                }else{
                    $data= Attendance::whereDate('created_at', '=', Carbon::today()->toDateString())->where('emp_id',$emp_id)->first();
                    $data->OUT = $earlyLeave;
                    $data->OUTstatus = $earlyLeave;
                    $data->save();
                    return response()->json([
                        'message' => 'Successfully Checked Out for Today',
                        'data'=> $data
                    ],201);
                }
            }else{
                return response()->json([
                    'message' => 'Give attendance first'
                ],403);
            }
        }   
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

    public function updateReason(Request $request){
        $validator = Validator::make($request->all(), [
            'action' => 'required|integer',
            'reason' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $user_id = auth()->user()->id;
        $checkIN = 1;
        $checkOut = 2;

        if($request->action == $checkIN){
            DB::table('attendances')->where('id', '=', $user_id)->whereDate('created_at', '=', Carbon::today()->toDateString())
                                    ->update(['lateINreason' => $request->reason]);
            return response()->json([
                'message' => 'Late entry reason added'
            ],200);
        }
        if($request->action == $checkOut){
            DB::table('attendances')->where('id', '=', $user_id)->whereDate('created_at', '=', Carbon::today()->toDateString())
                                    ->update(['earlyOUTreason' => $request->reason]);
            return response()->json([
                'message' => 'Early leave reason added'
            ],200);
        }     
    }

    public function deleteAttendance($id){
        Attendance::destroy($id);
        return response()->json([
            'message'=>'attendance deleted'
        ],204);
    }

    public function currentDayAttendanceStatus(Request $request){

        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id',$user_id)->value('emp_id');
        $date = $request->date;
        $currentDateTime = Carbon::now();
        $records = Attendance::where('emp_id', $emp_id)->get();
        $data = [];
        foreach($records as $value){
            $dateTime = new DateTime($value->created_at);
            $dateOnly = $dateTime->format('Y-m-d');
            if($dateOnly == $date){
                $created_at = Carbon::parse($value->created_at);
                if($value->OUT == null){
                    $timeDifference = $currentDateTime->diff($created_at);
                    $workingHour = $timeDifference->format('%H:%I:%S');
                    $value->workingHour = $workingHour;
                    $data = $value;
                }else{
                    $updated_at = Carbon::parse($value->updated_at);
                    $timeDifference = $created_at->diff($updated_at);
                    $workingHour = $timeDifference->format('%H:%I:%S');
                    $value->workingHour = $workingHour;
                    $data = $value;
                }
            }
        }
        if(!$data){
            return response()->json([
                'message'=> 'No attendance details found for '.$date
            ],404);
        }else{
            return response()->json([
                'message'=>'Attendance details for '.$date,
                'data'=> $data
            ],200);
        }

    }

    public function attendanceAddedByHR(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required|integer',
            'edit_reason' => 'required|string',
            'action'  => 'required|integer|in:1,2',
            'datetime'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $dateOnly = Carbon::parse($request->datetime)->format('Y-m-d');
        // dd($dateOnly);

        $checkIN = 1 ;
        $checkOUT = 2 ;
        $on_time_checkIn = 1;
        $on_time_check_out =1;
        $company_id = auth()->user()->company_id;
        $HR_id = auth()->user()->id;
        $HR_emp_id = Employee::where('id',$HR_id)->value('emp_id');
        $user_id = Employee::where('emp_id',$request->emp_id)->value('id');
        if($request->action == $checkIN){
            $attendance = Attendance::where('emp_id',$request->emp_id)->whereDate('created_at', '=', $dateOnly)->value('IN');
            if($attendance == $checkIN){
                return response()->json([
                    'message'=> 'Employee already Checked IN'
                ],400);
            }else{
                $data = new Attendance();
                $data->IN = $checkIN;
                $data->INstatus = $on_time_checkIn;
                $data->emp_id = $request->emp_id;
                $data->company_id = $company_id;
                $data->edit_reason = $request->edit_reason;
                $data->editedBY = $HR_emp_id;
                $data->id = $user_id;
                $data->created_at = $request->datetime;
                $data->save();
        
                return response()->json([
                    'message' => 'Attendance Accepted Successfully',
                    'data' => $data
                ], 201);
            }
        }elseif($request->action == $checkOUT){
            $attendance = Attendance::where('emp_id',$request->emp_id)->whereDate('created_at', '=', $dateOnly)->value('OUT');
            if($attendance == $checkOUT){
                return response()->json([
                    'message'=> 'Employee already Checked OUT'
                ],400);
            }else{
                $data= Attendance::whereDate('created_at', '=', $dateOnly)->where('emp_id',$request->emp_id)->first();
                    $data->OUT = $checkOUT;
                    $data->OUTstatus = $on_time_check_out;
                    $data->emp_id = $request->emp_id;
                    $data->company_id = $company_id;
                    $data->edit_reason = $request->edit_reason;
                    $data->editedBY = $HR_emp_id;
                    $data->id = $user_id;
                    $data->updated_at = $request->datetime;
                    $data->save();
                    return response()->json([
                        'message' => 'Successfully Checked Out',
                        'data'=> $data
                    ],200);
            }

        }
        
    }

    public function absentEmployee(){
        $company_id = auth()->user()->company_id;
        $currentDate = now()->toDateString();
        $missingEmployeeIds = Employee::where('company_id', $company_id)
            ->whereNotExists(function ($query) use ($currentDate) {
                $query->select(DB::raw(1))
                    ->from('attendances')
                    ->whereRaw('attendances.emp_id = employees.emp_id')
                    ->whereDate('attendances.created_at', $currentDate);
            })
            ->pluck('emp_id','name');

        return response()->json([
            'message'=>'Today Absent List',
            'data'=>$missingEmployeeIds
        ],200);
    }

    public function presentEmployeeList(Request $request){
        $company_id = auth()->user()->company_id;
        $date = $request->date;
        $data = Attendance::select(
                        'attendances.*',
                        'employees.name as employee_name',
                        'editedByEmployee.name as edited_by_name'
                    )
                    ->join('employees', 'attendances.emp_id', '=', 'employees.emp_id')
                    ->leftJoin('employees as editedByEmployee', 'attendances.editedBY', '=', 'editedByEmployee.emp_id')
                    ->whereDate('attendances.created_at', '=', $date)
                    ->where('attendances.company_id', $company_id)
                    ->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No Employee present for ' .' '.$date,
                'data'=>$data
            ],404);
        }
        return response()->json([
            'message'=> 'Present Employee list for'.' ' .$date,
            'data'=>$data
        ],200);
    }

    public function attendanceEditedByHr(Request $request){

        $validator = Validator::make($request->all(), [
            'emp_id' => 'required|integer',
            'edit_reason' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $Hr_user_id = auth()->user()->id;
        $Hr_emp_id = Employee::where('id',$Hr_user_id)->value('emp_id');
        $created_at = $request->created_at;
        $updated_at = $request->updated_at;
        $onTime = 1;
        $data = Attendance::find($request->attendance_id);
        // dd($data);
        if($created_at != null){
            $data->IN = $onTime;
            $data->INstatus = $onTime;
        }
        if($updated_at != null){
            $data->OUT = $onTime;
            $data->OUTstatus = $onTime;
        }
        
        $data->created_at = $created_at;
        $data->updated_at = $updated_at;
        $data->editedBY = $Hr_emp_id;
        $data->edit_reason = $request->edit_reason;
        $data->save();

        return response()->json([
            'message'=> 'Attendance Edited SUccessfully',
            'data'=>$data
        ],200);

    }
}
