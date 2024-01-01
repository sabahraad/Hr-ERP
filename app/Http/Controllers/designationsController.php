<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Designation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class designationsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    public function addDesignations(Request $request){
        $dept_id = $request->dept_id;
        $validator= Validator::make($request->all(), [
            'desigTitle' => [
                'required',
                Rule::unique('designations')->where(function ($query) use ($dept_id) {
                    return $query->where('dept_id', $dept_id);
                })
            ],
            'details'  => 'string',
            'dept_id' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;

        $data= new Designation;
        $data->desigTitle = $request->desigTitle;
        $data->details = $request->details;
        $data->dept_id = $request->dept_id;
        $data->save();

        return response()->json([
            'message' => 'Designation Added Successfully',
            'data'=> $data
        ],Response::HTTP_CREATED);

    }

    public function showDesignations($id){
        $company_id= auth()->user()->company_id;
        $data = Designation::where('designations.dept_id',$id)
                ->join("departments","departments.dept_id","=","designations.dept_id")
                ->get(['designations.*','departments.deptTitle']);

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
            'desigTitle' => 'required|string|unique:designations,desigTitle,' . $id . ',designation_id',
            'details'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $data = Designation::find($id);
        if($request->has('dept_id')){
            $data->dept_id = $request->dept_id;
        }
        $data->desigTitle = $request->desigTitle;
        $data->details = $request->details;
        
        if ($data->save()) {
            return response()->json([
                'message' => 'Designation updated Successfully',
                'data'=> $data
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

    public function designationNameList($id){
        $data = Designation::where('dept_id',$id)->pluck('desigTitle','designation_id');
        return $data;
    }
}
