<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class departmentController extends Controller
{

    public function addDepartment(Request $request){

        $validator= Validator::make($request->all(), [
            'deptTitle' => 'required|string',
            'details'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $data= new Department;
        $data->deptTitle = $request->deptTitle;
        $data->details = $request->details;
        $data->save();

        return response()->json([
            'message' => 'Department Added Successfully',
            'data'=>$request->all()
        ],Response::HTTP_CREATED);

    }

    public function showDepartment(){
        $data= Department::all();

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
        
        $data = Department::find($id);
        
        $data->deptTitle = $request->deptTitle;
        $data->details = $request->details;
        
        if ($data->save()) {
            return response()->json([
                'message' => 'Department updated Successfully',
                'data'=>$request->all()
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
