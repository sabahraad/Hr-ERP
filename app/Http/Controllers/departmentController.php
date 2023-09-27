<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Response;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class departmentController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addDepartment(Request $request){

        $validator= Validator::make($request->all(), [
            'deptTitle' => 'required|string',
            'details'  => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;

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

}
