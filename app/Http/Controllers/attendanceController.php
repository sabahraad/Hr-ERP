<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\AttendanceType;
use App\Models\Employee;
use App\Models\IP;
use App\Models\officeLocation;
use App\Models\User;
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

        $currentTime = Carbon::now()->toTimeString();
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');
        $company_id= auth()->user()->company_id;
        $checkIN = 1;
        $checkOut = 2;
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
                $data->edited = $request->edited;
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
            // $data= Attendance::whereDate('created_at', '=', Carbon::today()->toDateString())->where('id',$user_id)->first();
            // $data->lateINreason = $request->reason;
            // $data->save();
            DB::table('attendances')->where('id', '=', $user_id)->whereDate('created_at', '=', Carbon::today()->toDateString())
                                    ->update(['lateINreason' => $request->reason]);
            return response()->json([
                'message' => 'Late entry reason added'
            ],200);
        }
        if($request->action == $checkOut){
            // $data= Attendance::whereDate('created_at', '=', Carbon::today()->toDateString())->where('id',$user_id)->first();
            //  $data->earlyOUTreason = $request->reason;
            // $data->save();
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

}
