<?php

namespace App\Http\Controllers;

use App\Models\Timeline;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class timelineController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addTimeline(Request $request){
        $validator = Validator::make($request->all(), [
            'fetch_time' => 'required|string',
            'emp_id' => 'string|unique:timeline'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id = auth()->user()->company_id;
        $data = new Timeline();
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
        $data = Timeline::where('company_id',$company_id)->get();
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
        $data = Timeline::find($id);
        $data->fetch_time = $request->fetch_time;
        $data->save();
        return response()->json([
            'message'=>'Timeline updated',
            'data'=>$data
        ],200);
    }

    public function deleteTimeline($id){
        Timeline::destroy($id);
        return response()->json([
            
        ],204);
    }
}
