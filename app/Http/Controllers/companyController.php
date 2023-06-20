<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class companyController extends Controller
{
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
                'errors' => $validator->errors(),
            ], 422);
        }

        $imageName =  time() . '.' . $request->logo->getClientOriginalName();

        $request->logo->move(public_path('images'), $imageName);

        $data= new Company;
        $data->companyName = $request->companyName;
        $data->address = $request->address;
        $data->logo = $imageName;
        $data->contactNumber = $request->contactNumber;
        $data->companyDetails = $request->companyDetails;
        if($data->save()){
            return response()->json([
                'result' => 'Company added successfully'
            ]);
        }else{
            return response()->json([
                'result' => 'Something Went Wrong'
            ]);
        }
    }

    public function showCompany(){
        $data= Company::all();
        $monir= Company::pluck('contactNumber');
        dd($monir);
                return response()->json($data);
        // return $data;

    }

    public function editCompany(Request $request){
        
        $id = $request->id;
        if($request->hasFile('logo')){
            $imageName =  time() . '.' . $request->logo->extension();

            $request->logo->move(public_path('images'), $imageName);
            $data = Company::find($id);
            $data->logo = $imageName;
            $data->save();
        }

        $data = Company::find($id);
        
        $data->companyName = $request->companyName;
        $data->address = $request->address;
        $data->contactNumber = $request->contactNumber;
        $data->companyDetails = $request->companyDetails;
       

        if ($data->save()) {
            return response()->json([
                'result' => 'Company updated Successfully'
            ]);
        }else{
            return response()->json([
                'result'=> 'Something Went Wrong'
            ]);
        }

    }

    public function deleteCompany($id){
        // dd($id);
        Company::destroy($id);        
        return response()->json(['message' => 'Company deleted successfully']);

    }
}
