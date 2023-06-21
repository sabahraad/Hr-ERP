<?php

namespace App\Http\Controllers;

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
                'errors' => $validator->errors(),
            ], 422);
        }

    
        $data= new Department;
        $data->deptTitle = $request->deptTitle;
        $data->details = $request->details;
        $data->save();

        return response()->json([
            'result' => 'Department Added Successfully'
        ]);

    }

    public function showDepartment(){
        $data= Department::all();
        return response()->json($data);
    }

    public function editDepartment(Request $request,$id){
        
        $data = Department::find($id);
        
        $data->deptTitle = $request->deptTitle;
        $data->details = $request->details;
        
        if ($data->save()) {
            return response()->json([
                'result' => 'Department updated Successfully'
            ]);
        }else{
            return response()->json([
                'result'=> 'Something Went Wrong'
            ]);
        }


    }

    public function deleteDepartment($id){
        
        Department::find($id)->delete();
        return response()->json([
            'result' => 'Department Deleted'
        ]);

    }

}
