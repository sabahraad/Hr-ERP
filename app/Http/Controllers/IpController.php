<?php

namespace App\Http\Controllers;

use App\Models\IP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class IpController extends Controller
{
    protected $validationRules = [
        'ip' => 'required|string',
        'wifiName' => 'string',
        'company_id' => 'required|integer', 
        'status' => 'required|boolean', 
    ];
    
    public function addIP(Request $request){

        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $ip = new IP();
        $ip->ip = $request->ip;
        $ip->wifiName = $request->wifiName;
        $ip->company_id = $request->company_id;
        $ip->status = $request->status;
        $ip->save();

        return response()->json([
            'message' => 'IP added successfully',
            'data' => $request->all()
        ], 201);
    }

    public function updateIP(Request $request,$id){

        $validator = Validator::make($request->all(), $this->validationRules);

        if($validator->fails()){
            return response()->json([
                'error'=> $validator->errors()
            ],422);
        }

        $ip = IP::find($id);
        $ip->ip= $request->ip;
        $ip->wifiName = $request->wifiName;
        $ip->company_id = $request->company_id;
        $ip->status = $request->status;
        $ip->save();

        return response()->json([
            'message'=>'IP Updated Successfully',
            'data' => $request->all()
        ],200);
    }

    public function showIP(){
        $data= IP::all();

        if (count($data) === 0) {
            return response()->json([
                'message' => 'Please Add IP/Wifi List First',
            ],Response::HTTP_NOT_FOUND);

        }else{

            return response()->json([
                'message' => 'IP Details',
                'data' => $data,
    
            ],Response::HTTP_OK);
        }
    }

    public function deleteIP($id){

        IP::where('ip_id',$id)->delete();
        return response()->json([
            'message'=>'IP Deleted Successfully'
        ],200);
    }
}
