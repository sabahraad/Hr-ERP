<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class companyController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function showCompany(){
        
        $company_id= auth()->user()->company_id;
        
        $data= Company::where('company_id',$company_id)->get();
        
        if (count($data) === 0) {
            return response()->json([
                'message' => 'Please Add Company First',
                'data'=> $data
            ],Response::HTTP_NOT_FOUND);

        }else{

            return response()->json([
                'message' => 'Company Details',
                'data' => $data,
    
            ],Response::HTTP_OK);
        }
    }

    public function editCompany(Request $request){

        $company_id= auth()->user()->company_id;
        $validator = Validator::make($request->all(), [
            'companyName' => 'unique:companies,companyName,' . $company_id . ',company_id',
            'address' => 'string',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'companyDetails' => 'string',
            'contactNumber' => 'string',    
        ]);
        Rule::unique('companies', 'companyName')->ignore($company_id, 'company_id');
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $data = Company::find($company_id);
        if(!$data){
            return response()->json([
                'message' => 'Add Company First'
            ],Response::HTTP_NOT_FOUND);
        }

        if($request->hasFile('logo')){
            $imageName =  time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
            $data->logo = $imagePath;
        }
        
        $data->companyName = $request->companyName;
        $data->address = $request->address;
        $data->contactNumber = $request->contactNumber;
        $data->companyDetails = $request->companyDetails;
        $data->save();
        
        return response()->json([
            'message' => 'Company updated Successfully',
            'data' => $data,
        ],Response::HTTP_OK);

    }

    public function deleteCompany(){

        $company_id= auth()->user()->company_id;
        $company = Company::find($company_id);
        $company->users()->delete();  
        $company->delete();
        return response()->json([
            'message' => 'Company deleted successfully'
        ]);
    }
}
