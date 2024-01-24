<?php

namespace App\Http\Controllers;

use App\Models\SalarySetting;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class salarySettingController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createSalarySetting(Request $request){
        $validator = Validator::make($request->all(), [
            'components' => 'required|array',
            'components.*.name' => 'required|string',
            'components.*.percentage' => ['required', 'numeric', 'min:0']
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $components = $request->input('components');

        $totalPercentage = collect($components)->sum('percentage');

        if ($totalPercentage > 100) {
            return response()->json(['error' => 'The total percentage cannot exceed 100.'], 400);
        }

        $company_id = auth()->user()->company_id;
        
        $data = new SalarySetting();
        $data->company_id = $company_id;
        $data->components = $request->components;
        if($data->save()){
            return response()->json([
                'message'=>'Your Salary Setting Successfully Added',
                'data'=>$data
            ],201);
        }else{
            return response()->json([
                'message'=>'Something Went Wrong',
                'data'=>$data  
            ],500);
        }
    }

    public function showSalarySetting(){
        $company_id = auth()->user()->company_id;
        $data = SalarySetting::where('company_id',$company_id)->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Salary Setting',
                'data'=>$data
            ],200);
        }
    }

    public function editSalarySetting(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'components' => 'required|array',
            'components.*.name' => 'required|string',
            'components.*.percentage' => ['required', 'numeric', 'min:0']
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $components = $request->input('components');

        $totalPercentage = collect($components)->sum('percentage');

        if ($totalPercentage > 100) {
            return response()->json(['error' => 'The total percentage cannot exceed 100.'], 400);
        }

        $data = SalarySetting::find($id);
        $data->components = $request->components ?? $data->components;
        if($data->save()){
            return response()->json([
                'message'=>'Salary Setting Updated Successfully',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Something Went Wrong',
            ],500);
        }
    }

    public function deleteSalarySetting($id){
        SalarySetting::destroy($id);
        return response()->json([
            'message'=>'deleted successfully'
        ],204);
    }
}
