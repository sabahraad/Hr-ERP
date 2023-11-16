<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Approvers;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class approversController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    public function addApprovers(Request $request){

        $validator = Validator::make($request->all(), [
            'deptId' => 'required|integer',
            'emp_id' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $deptName = Department::where('dept_id',$request->deptId)->value('deptTitle');
        $approver_name = Employee::where('emp_id',$request->emp_id)->value('name');
        // $priority = Approvers::where('deptId',$request->deptId)->get();
        // $count = $priority->count();
        // $priority = $count + 1;
        $company_id= auth()->user()->company_id;

        $data = new Approvers();
        $data->deptId = $request->deptId;
        $data->deptName = $deptName;
        $data->emp_id = $request->emp_id;
        $data->approver_name = $approver_name;
        $data->company_id = $company_id;
        $data->priority =$request->priority;
        $data->save();
        return response()->json([
            'message'=>'Approvers are Added',
            'data'=>$data
        ],201);
    }

    public function approversList($id){

        $company_id= auth()->user()->company_id;
        $data = Approvers::where('company_id',$company_id)->where('deptId',$id)->get();
        
        if($data->isEmpty()){
            return response()->json([
                'message'=>'No Approvers found'
            ],404);
        }else{
            return response()->json([
                'message'=> 'Approvers List',
                'data'=> $data
            ],200);
        }
    }

    public function editApprovers(Request $request,$id){
        $company_id= auth()->user()->company_id;
        $validator = Validator::make($request->all(), [
            'deptId' => 'required|integer',
            'deptName' => 'required|string',
            'emp_id' => 'required|integer',
            'approver_name' => 'required|string',
            'priority' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $data = Approvers::find($id);
        if(!$data){
            return response()->json([
                'message'=>'Approvers Details not found'
            ],404);
        }
        $data->deptId = $request->deptId;
        $data->deptName = $request->deptName;
        $data->emp_id = $request->emp_id;
        $data->approver_name = $request->approver_name;
        $data->company_id = $company_id;
        $data->priority =$request->priority;
        $data->save();
        return response()->json([
            'message'=>'Approvers Details Updated',
            'data'=>$data
        ],200);
    }

    public function deleteApprovers($id){
        $data = Approvers::where('approvers_id',$id)->value('deptId');
        Approvers::destroy($id);
        return response()->json([
            'message'=>'Approvers Details deletes successfully',
            'data'=>$data
        ],200);
    }
}
