<?php

namespace App\Http\Controllers;

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

    
        $data= new Designation;
        $data->desigTitle = $request->desigTitle;
        $data->details = $request->details;
        $data->save();

        return response()->json([
            'result' => 'Designation Added Successfully'
        ]);

    }

    public function showDesignations(){
        $data= Designation::all();
        return response()->json($data);
    }

    public function editDesignations(Request $request,$id){
        
        $data = Designation::find($id);
        
        $data->desigTitle = $request->desigTitle;
        $data->details = $request->details;
        
        if ($data->save()) {
            return response()->json([
                'result' => 'Designation updated Successfully'
            ]);
        }else{
            return response()->json([
                'result'=> 'Something Went Wrong'
            ]);
        }


    }

    public function deleteDesignations($id){
        
        Designation::find($id)->delete();
        return response()->json([
            'result' => 'Designation Deleted'
        ]);

    }
}
