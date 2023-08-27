<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string',
            'address' => 'required|string',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'companyDetails' => 'required',
            'contactNumber' => 'required',    
        ]);
        $company_id= auth()->user()->company_id;
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
            $data->save();
        }

        $data = Company::find($company_id);
        
        $data->companyName = $request->companyName;
        $data->address = $request->address;
        $data->contactNumber = $request->contactNumber;
        $data->companyDetails = $request->companyDetails;
        $data->save();

        $data= Company::where('company_id',$company_id)->get();
        
        return response()->json([
            'message' => 'Company updated Successfully',
            'data' => $data,
        ],Response::HTTP_OK);

    }

    public function deleteCompany(){

        $company_id= auth()->user()->company_id;
        Company::where('company_id',$company_id)->delete();        
        return response()->json([
            'message' => 'Company deleted successfully'
        ]);

    }

}
