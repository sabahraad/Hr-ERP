<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Support\Facades\DB;
use App\Models\SalarySetting;
use App\Models\tempSalarySetting;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;

class salarySettingController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createSalarySetting(Request $request){
        $company_id = auth()->user()->company_id;
        $data = tempSalarySetting::where('company_id',$company_id)->get();
        $components = [];
        $percentage = [];
        foreach ($data as $item) {
            $percentage[] = $item->percentage;
            $components[] = [
                'name' => $item->name,
                'percentage' => (float) $item->percentage,
            ];
        }
        $totalPercentage = array_sum(array_map('floatval', $percentage));
        // dd($totalPercentage);
        if($totalPercentage == 100){
            $data = SalarySetting::where('company_id',$company_id)->first(); 
            if(!$data){
                $data = new SalarySetting();
                $data->company_id = $company_id;
                $data->components = $components;
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
            }else{
                $data->components = $components;
                $data->save();
                return response()->json([
                    'message' => 'Salary Setting Updated Successfully.',
                ], 200);
            }
        }else{
            return response()->json([
                'message'=>'Total Percentage Have To Be 100'
            ],403);
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

    public function tempSalarySetting(Request $request){
        $company_id = auth()->user()->company_id;
        $name = $request->name;
        $percentage = $request->percentage;

        $result = tempSalarySetting::where('company_id', $company_id)->sum('percentage');
        $totalpercentage = $result+ $percentage;

        if($totalpercentage > 100){
            return response()->json([
                'message'=> 'Total Percentage Can Not Be More Then 100'
            ],422);
        }else{
                $data = new tempSalarySetting();
                $data->name = $name;
                $data->percentage = $percentage;
                $data->company_id = $company_id;
                $data->save();
                
                $data = tempSalarySetting::where('company_id',$company_id)->get();
                return response()->json([
                    'message'=>'Salary Setting Updated',
                    'data'=>$data
                ],201);
            }
    }

    public function tempSalarySettingList(){
        $company_id = auth()->user()->company_id;
        $data = tempSalarySetting::where('company_id',$company_id)->get();

        return response()->json([
            'message'=>'Salary Breakdown list',
            'data'=>$data
        ],200);
    }

    public function deletetempSalarySetting($id){
        tempSalarySetting::destroy($id);
        return response()->json([
            'message'=>'successfully deleted'
        ],204);
    }
}
