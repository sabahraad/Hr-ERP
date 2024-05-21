<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class packageController extends Controller
{

    public function createPackage(Request $request){
        // if($request->package_price <= $request->per_user_price){
        //     return redirect()->back()->with('error','Package Price Can Not Be Smaller Then Per User Price');
        // }
        $data = new Package();
        $data->package_name = $request->package_name;
        $data->package_price = $request->package_price;
        $data->per_user_price = $request->per_user_price;
        $data->description = $request->description;
        $data->save();
        return redirect()->back()->with('success','Package Created');
    }

    public function packageList(){
        $data = Package::all();
        return view('SuperAdmin.packageList',compact('data'));

    }

    public function editPackageform($id){
        $package = Package::find($id);
        return view('SuperAdmin.editPackage',compact('package'));
    }

    public function editPackage(Request $request,$id){
        $data = Package::find($id);
        $data->package_name = $request->package_name ?? $data->package_name;
        $data->package_price = $request->package_price ?? $data->package_price;
        $data->per_user_price = $request->per_user_price ?? $data->per_user_price;
        $data->description = $request->description ?? $data->description;
        $data->save();
        return redirect()->route('super-admin.packageList')->with('success','Package Updated');
    }

    public function deletePackage(Request $request){
        Package::destroy($request->packages_id);
        return redirect()->back()->with('success','Package Deleted Successfully');
    }
}
