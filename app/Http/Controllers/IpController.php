<?php

namespace App\Http\Controllers;

use App\Models\IP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

class IpController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    protected $validationRules = [
        'ip' => 'required|array',
        'wifiName' => 'string',
        'status' => 'required|boolean', 
    ];
    
    public function addIP(Request $request){
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $company_id= auth()->user()->company_id;
        $id =  IP::where('company_id',$company_id)->value('ip_id');
         
        if($id){
            $data = IP::find($id);
            $data->ip = json_encode($request->ip);
            $data->wifiName = $request->wifiName;
            $data->status = $request->status;
            $data->save();
            return response()->json([
                'message' => 'Ip list has been updated',
                'data' => $data
            ],200);
        }else{

            $ip = new IP();
            $ip->ip = json_encode($request->ip);
            $ip->wifiName = $request->wifiName;
            $ip->company_id = $company_id;
            $ip->status = $request->status;
            $ip->save();
    
            return response()->json([
                'message' => 'IP added successfully',
                'data' => $request->all()
            ], 201);

        }

      
    }

    public function showIP(){
        $company_id= auth()->user()->company_id;
        $data= IP::where('company_id',$company_id)->get();

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

    public function deleteIP(){

        $company_id= auth()->user()->company_id;
        IP::where('company_id',$company_id)->delete();
        return response()->json([
            'message'=>'IP Deleted Successfully'
        ],200);
    }
}
