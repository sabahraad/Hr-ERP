<?php

namespace App\Http\Controllers;

use App\Models\officeLocation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class officeLocationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createOfficeLocation(Request $request){

        $validator = Validator::make($request->all(), [
            'location_name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric',
            'status' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;

        $data = new officeLocation();
        $data->location_name = $request->location_name;
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->radius = $request->radius;
        $data->status = $request->status;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=>'Office Location Added Successfully',
            'data'=>$data
        ],201);
    }

    public function OfficeLocationList(){
        $company_id= auth()->user()->company_id;
        $data = officeLocation::where('company_id',$company_id)->get();
        return response()->json([
            'message'=> 'Office Location List',
            'data'=>$data
        ],200);
    }

    public function updateOfficeLocation(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'location_name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric',
            'status' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id= auth()->user()->company_id;
        $data=officeLocation::find($id);
        $data->location_name = $request->location_name;
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->radius = $request->radius;
        $data->status = $request->status;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=>'Office Location Updated Successfully',
            'data'=>$data
        ],200);

    }

    public function deleteOfficeLocation($id){
        officeLocation::where('office_locations_id',$id)->delete();        
        return response()->json([
            'message' => 'Office Location deleted successfully'
        ]);
    }
 
}
