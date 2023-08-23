<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;

class companyController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addCompany(Request $request){

        
        $validator= Validator::make($request->all(), [
            'companyName' => 'required|string',
            'address' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'companyDetails' => 'required',
            'contactNumber' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $imageName =  time() . '.' . $request->logo->extension();
        $request->logo->move(public_path('images'), $imageName);

        $data = new Company();
            
        $data->companyName = $request->companyName;
        $data->logo = $imageName;
        $data->address = $request->address;
        $data->contactNumber = $request->contactNumber;
        $data->companyDetails = $request->companyDetails;
        $data->save();

        return response()->json([
            'message' => 'Company Created Successfully',
            'data'=> $request->all()
            
        ],Response::HTTP_CREATED);
        

    }

    public function showCompany(){

        $data= Company::all();
        
        if (count($data) === 0) {
            return response()->json([
                'message' => 'Please Add Company First',
            ],Response::HTTP_NOT_FOUND);

        }else{

            return response()->json([
                'message' => 'Company Details',
                'data' => $data,
    
            ],Response::HTTP_OK);
        }
    }

    public function editCompany(Request $request,$id){

        if($request->hasFile('logo')){
            $imageName =  time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images'), $imageName);
            $data = Company::find($id, 'company_id');
            $data->logo = $imageName;
            $data->save();
        }

        $data = Company::find($id);
        
        $data->companyName = $request->companyName;
        $data->address = $request->address;
        $data->contactNumber = $request->contactNumber;
        $data->companyDetails = $request->companyDetails;
        $data->save();

        $data= Company::where('company_id',$id)->get();
        
        return response()->json([
            'message' => 'Company updated Successfully',
            'data' => $data,
        ],Response::HTTP_OK);

    }

    public function deleteCompany($id){
       
        Company::where('company_id',$id)->delete();        
        return response()->json([
            'message' => 'Company deleted successfully'
        ]);

    }

    public function adminShow(){

        $data = Company::withTrashed()->get();
        return response()->json($data);
    }

   
}
