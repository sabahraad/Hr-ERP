<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TimelineSetting;
use App\Models\TimelineTrack;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class timelineController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addTimeline(Request $request){
        $validator = Validator::make($request->all(), [
            'fetch_time' => 'required|string',
            'emp_id' => 'required|unique:timeline_settings'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id = auth()->user()->company_id;
        $data = new TimelineSetting();
        $data->fetch_time = $request->fetch_time;
        $data->emp_id = $request->emp_id;
        $data->company_id = $company_id;
        if($data->save()){
            return response()->json([
                'message'=>'Timeline added successfully',
                'data'=>$data
            ],201);
        }else{
            return response()->json([
                'message'=>'Somethimg Went Wrong'
            ],500);
        }
    }

    public function timelineList(){
        $company_id = auth()->user()->company_id;
        $data = TimelineSetting::where('timeline_settings.company_id',$company_id)
                                ->join('employees','employees.emp_id','=','timeline_settings.emp_id')
                                ->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Timeline List',
                'data'=>$data
            ],200);
        }
    }

    public function editTimeline(Request $request,$id){
        $data = TimelineSetting::find($id);
        $data->fetch_time = $request->fetch_time;
        $data->save();
        return response()->json([
            'message'=>'Timeline updated',
            'data'=>$data
        ],200);
    }

    public function deleteTimeline($id){
        TimelineSetting::destroy($id);
        return response()->json([
            
        ],204);
    }

    public function storeTimelineTrack(Request $request){

        $validator = Validator::make($request->all(), [
            'track_date' => 'required|date_format:Y-m-d',
            'latitude' => 'required|numeric ',
            'longitude' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id',$user_id)->value('emp_id');
        $company_id = auth()->user()->company_id;
        $currentTime = date('H:i:s');
        $existingRecord = TimelineTrack::where('track_date', $request->track_date)
                               ->where('emp_id', $emp_id)
                               ->first();

        if ($existingRecord) {
            $existingLocationData = json_decode($existingRecord->location, true) ?? [];
            $newLocationData = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'time' => $currentTime
            ];
            $existingLocationData[] = $newLocationData;
            $existingRecord->location = json_encode($existingLocationData);
            $existingRecord->save();

            return response()->json([
                'message' => 'TimeLine Track Successfully Stored',
                'data' => $existingRecord
            ], 201);
        } else {
            $data = new TimelineTrack();
            $data->track_date = $request->track_date;
            $newLocationData = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'time' => $currentTime
            ];
            $data->location = json_encode([$newLocationData]);
            $data->emp_id = $emp_id;
            $data->company_id = $company_id;
            $data->save();

            return response()->json([
                'message' => 'TimeLine Track Successfully Stored',
                'data' => $data
            ], 201);
        }
    }

    public function dateWiseTrackOfEmployee(Request $request){
        $validator = Validator::make($request->all(), [
            'track_date' => 'required|date_format:Y-m-d',
            'emp_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $date = $request->track_date;
        $emp_id = $request->emp_id;
        $data = TimelineTrack::where('track_date',$date)->where('emp_id',$emp_id)->first();
        if(!$data){
            return response()->json([
                'message'=>'No Timeline Track found',
                'data'=>$data
            ],404);
        }else{
            return response()->json([
                'message'=>'Timeline Track details',
                'data'=>$data
            ],200);
        }
    }
}
