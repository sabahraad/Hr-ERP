<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use App\Models\Company;
use Illuminate\Http\Response;
use App\Models\Department;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class departmentController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addDepartment(Request $request){
        $company_id= auth()->user()->company_id;

        $validator= Validator::make($request->all(), [
            'deptTitle' => 'required|string',
            'details'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $data= new Department;
        $data->deptTitle = $request->deptTitle;
        $data->details = $request->details;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message' => 'Department Added Successfully',
            'data'=>$data
        ],Response::HTTP_CREATED);

    }

    public function showDepartment(){
        $company_id= auth()->user()->company_id;
        $data= Department::where('company_id',$company_id)->get();
        
        if (count($data) === 0) {
            return response()->json([
                'message' => 'Please Add Department First',
            ],Response::HTTP_NOT_FOUND);

        }else{

            return response()->json([
                'message' => 'Department List',
                'data' => $data,
    
            ],Response::HTTP_OK);
        }
        
    }

    public function editDepartment(Request $request,$id){
        
        $validator= Validator::make($request->all(), [
            'deptTitle' => 'required|string',
            'details'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $company_id= auth()->user()->company_id;
        $data = Department::where('company_id',$company_id)->find($id);
        $data->deptTitle = $request->deptTitle;
        $data->details = $request->details;
        
        if ($data->save()) {
            return response()->json([
                'message' => 'Department updated Successfully',
                'data'=>$data
            ],Response::HTTP_OK);
        }else{
            return response()->json([
                'message'=> 'Something Went Wrong'
            ],Response::HTTP_BAD_GATEWAY);
        }

    }

    public function deleteDepartment($id){
        
        Department::find($id)->delete();
        return response()->json([
            'message' => 'Department Deleted'
        ]);

    }

    public function departmentNameList(){
        $company_id= auth()->user()->company_id;
        $data = Department::where('company_id',$company_id)->pluck('deptTitle','dept_id');
        return $data;
    }

    public function deptDetails($id){
        $data = Department::find($id);
        if(!$data){
            return response()->json([
                'message'=>'NO Data Found',
                'data'=>$data
            ],404);
        }else{
            return response()->json([
                'message'=>'Department Details',
                'data'=>$data
            ],200);
        }
    }
    
}
