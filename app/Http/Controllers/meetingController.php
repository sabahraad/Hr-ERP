<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Meeting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class meetingController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createMeeting(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:meeting,appointment',
            'guest_company_name' => 'required_if:type,appointment',
            'meeting_datetime' => 'required|date_format:Y-m-d H:i:s',
            'attendee_id' =>'required_if:type,meeting'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->user()->id;
        $company_id = auth()->user()->company_id;
        $creator_id = Employee::where('id',$user_id)->value('emp_id');
        
        $data = new Meeting();
        $data->type = $request->type;
        $data->meeting_datetime = $request->meeting_datetime;
        $data->guest_company_name = $request->guest_company_name;
        $data->creator_id = $creator_id;
        $data->attendee_id = $request->attendee_id;
        $data->company_id = $company_id;

        $data->save();

        return response()->json([
            'message'=>'meeting schedule is created',
            'data'=>$data

        ],201);
    }

    public function editMeeting(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:meeting,appointment',
            'guest_company_name' => 'required_if:type,appointment',
            'meeting_datetime' => 'required|date_format:Y-m-d H:i:s',
            'attendee_id' =>'required_if:type,meeting',
            'status'=>'required|in:cancel,complete,reschedule'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $user_id = auth()->user()->id;
        $creator_id = Employee::where('id',$user_id)->value('emp_id');

        $data= Meeting::find($id);

        if($data->creator_id == $creator_id){
            if($data->status == 'complete' || $data->status == 'cancel'){
                return response()->json([
                    'message'=> 'Complete or canceled meeting can not be edited',
                    'data'=>$data
                ],200);
            }else{
                if($request->status == 'complete'){
                    $data->status = 'complete';
                    $data->save();
                    return response()->json([
                        'message'=> 'Meeting Completed Successfully',
                        'data'=>$data
                    ],200);
                }elseif($request->status == 'cancel'){
                    $data->status = 'cancel';
                    $data->save();
                    return response()->json([
                        'message'=> 'Meeting Canceled Successfully',
                        'data'=>$data
                    ],200);
                }else{
                    $data->type = $request->type ?? $data->type;
                    $data->meeting_datetime = $request->meeting_datetime??$data->meeting_datetime;
                    $data->guest_company_name = $request->guest_company_name ?? $data->guest_company_name;
                    $data->attendee_id = $request->attendee_id ?? $data->attedee_id;
                    $data->status = 'pending';
                    $data->save();
                    return response()->json([
                        'message'=> 'Meeting rescheduled Successfully',
                        'data'=>$data
                    ],200);
                }
            }
        }else{
            return response()->json([
                'message'=> 'You can not edit this meeting,only meeting creator can change'
            ],200);
        }



    }

    public function creatorMeeitngList(){
        $user_id = auth()->user()->id;
        $creator_id = Employee::where('id',$user_id)->value('emp_id');

        $data = Meeting::where('creator_id',$creator_id)->get();

        if(empty($data)){
            return response()->json([
                'message'=>'no data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Meeting List',
                'data'=>$data
            ],200);
        }

    }

    public function meetingList(){
        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id',$user_id)->value('emp_id');
        $data = Meeting::where(function ($query) use ($emp_id) {
                            $query->where('creator_id', $emp_id)
                                ->orWhere('attendee_id', $emp_id);
                        })
                        ->orderBy('meeting_datetime', 'desc')
                        ->take(5)
                        ->get();

        if(empty($data)){
            return response()->json([
                'message'=>'No Meeting Found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Meeting List',
                'data'=>$data
            ],200);
        }
            
    }
    public function deleteMeeting($id){

    }
    
}
