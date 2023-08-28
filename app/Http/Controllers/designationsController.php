<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Designation;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class designationsController extends Controller
{
    public function addDesignations(Request $request){

        $validator= Validator::make($request->all(), [
            'desigTitle' => 'required|string',
            'details'  => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;

        $data= new Designation;
        $data->desigTitle = $request->desigTitle;
        $data->details = $request->details;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message' => 'Designation Added Successfully',
            'data'=> $request->all()
        ],Response::HTTP_CREATED);

    }

    public function showDesignations(){
        $company_id= auth()->user()->company_id;
        $data= Designation::where('company_id',$company_id)->get();

        if (count($data) === 0) {
            return response()->json([
                'message' => 'Please Add Designation First',
            ],Response::HTTP_NOT_FOUND);

        }else{

            return response()->json([
                'message' => 'Designation List',
                'data' => $data,
    
            ],Response::HTTP_OK);
        }
    }

    public function editDesignations(Request $request,$id){

        $validator= Validator::make($request->all(), [
            'desigTitle' => 'required|string',
            'details'  => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $data = Designation::find($id);
        
        $data->desigTitle = $request->desigTitle;
        $data->details = $request->details;
        
        if ($data->save()) {
            return response()->json([
                'message' => 'Designation updated Successfully',
                'data'=> $request->all()
            ],Response::HTTP_OK);
        }else{
            return response()->json([
                'message'=> 'Something Went Wrong'
            ],Response::HTTP_BAD_GATEWAY);
        }


    }

    public function deleteDesignations($id){
        
        Designation::find($id)->delete();
        return response()->json([
            'message' => 'Designation Deleted'
        ]);

    }
}
