<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class visitController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createVisit(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'desc' => 'string',
            'visit_time' => 'required|date_format:Y-m-d H:i:s'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id = auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');

        $data = new Visit();
        $data->title = $request->title;
        $data->desc = $request->desc;
        $data->visit_time = $request->visit_time;
        $data->company_id = $company_id;
        $data->emp_id = $emp_id;

        $data->save();

        return response()->json([
            'message'=>'Visit Details Added',
            'data'=>$data
        ],201);
    }

    public function visitList(){
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');

        $data = Visit::where('emp_id',$emp_id)
                    ->where('status','pending')
                    ->orderBy('created_at','desc')
                    ->get();
        if(empty($data)){
            return response()->json([
                'message'=>'No data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Visit List',
                'data'=>$data
            ],200);
        }

    }

    public function individualVisitDeatils($id){

        $data = Visit::find($id);
        
        if(empty($data)){
            return response()->json([
                'message'=>'No data found',
                'data'=>$data

            ],200);
        }else{
            return response()->json([
                'message'=>'Visit Details',
                'data'=>$data

            ],200);
        }

    }

    public function completeVisit(Request $request,$id){
       
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'desc' => 'string',
            'visit_time' => 'required|date_format:Y-m-d H:i:s',
            'latitude'=>'required|numeric',
            'longtitude'=>'required|numeric',
            'attachment' => 'mimes:jpg,jpeg,png,gif,svg,pdf,xlsx,xls'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $data = Visit::find($id);
        if($data->status == 'complete'){
            return response()->json([
                'message'=>'Visit is already completed'
            ],200);
        }elseif($data->status == 'cancel'){
            return response()->json([
                'message'=>'you can not change cancel visit'
            ],200);
        }
        $data->title = $request->title;
        $data->desc = $request->desc;
        $data->visit_time = $request->visit_time;
        $data->latitude = $request->latitude;
        $data->longtitude = $request->longtitude;
        $data->status = 'complete';
        $data->update_time = now();
        if($request->has('attachment')){
            
            $extension = $request->attachment->getClientOriginalExtension();
            if ($extension === 'pdf') {
                $pdfPath = $request->attachment->storeAs('pdfs', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$pdfPath;
            }
        
            if (in_array($extension, ['jpg','jpeg', 'png', 'gif', 'svg'])) {
                    $imagePath = $request->attachment->storeAs('images', time() . '.' . $extension, 'public');
                    $data->attachment = 'storage/'.$imagePath;
            }
        
            if (in_array($extension, ['xlsx', 'xls'])) {
                $excelPath = $request->attachment->storeAs('excels', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$excelPath;
            }
        }

        if($data->save()){
            return response()->json([
                'message'=>'Your Visit is completed',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Something Went Wrong',
            ],500);
        }
    }

    public function editVisit(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'desc' => 'string',
            'visit_time' => 'required|date_format:Y-m-d H:i:s',
            'status'=>'required|in:cancel,rescheduled',
            'cancel_reason' => 'required_if:status,cancel',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $data= Visit::find($id);
        if($data->status == 'complete'){
            return response()->json([
                'message'=>'You can not change complete visit'
            ],200);
        }
        if($request->status == 'cancel'){
            $data->title = $request->title;
            $data->desc = $request->desc;
            $data->visit_time = $request->visit_time;
            $data->status = $request->status;
            $data->cancel_reason = $request->cancel_reason;
            $data->update_time = now();
            $data->save();
            return response()->json([
                'message'=>'Visit Canceled',
                'data'=>$data

            ],200);
        }else{
            $data->title = $request->title;
            $data->desc = $request->desc;
            $data->visit_time = $request->visit_time;
            $data->status = 'pending';
            $data->save();
            return response()->json([
                'message'=>'Visit Rescheduled',
                'data'=>$data
            ],200);
        }
    }

    public function completeVisitList(){
        $user_id = auth()->user()->id;
        $emp_id= Employee::where('id',$user_id)->value('emp_id');
        $data=Visit::whereIn('status', ['complete', 'cancel'])
                    ->where('emp_id', $emp_id)
                    ->get();
        if(count($data)==0){
            return response()->json([
                'message'=>'NO Visit History Found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Visit History',
                'data'=>$data

            ],200);
        }
    }
}
