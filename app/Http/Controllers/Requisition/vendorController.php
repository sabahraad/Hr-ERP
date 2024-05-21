<?php

namespace App\Http\Controllers\Requisition;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\RequisitionCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;

class vendorController extends Controller
{
    public function createVendor(Request $request){
        $data = new Vendor();
        $data->vendor_name = $request->vendor_name;
        if ($request->agreement_attachment) {
            $file = $request->file('agreement_attachment');
            $extension = $file->getClientOriginalExtension();
            $pdfPath = $file->storeAs('pdfs', time() . '.' . $extension, 'public');
            $data->agreement_attachment = 'storage/' . $pdfPath;
        }
        $data->requisition_categories_id = $request->requisition_categories_id;
        $data->save();
        return redirect()->route('vendorList')->with('success','Vendor Created Successfully');
    }

    public function vendorList(){
        $company_id = Session('company_id');
        $data = RequisitionCategory::where('company_id',$company_id)->get();
        $result = Vendor::with('requisitionCategory')
                        ->whereHas('requisitionCategory', function ($query) use ($company_id) {
                            $query->where('company_id', $company_id);
                        })
                        ->get();
        return view('Requisition.vendorList',compact('data','result'));
    }

    public function findVendor(Request $request){
        $company_id = Session('company_id');
        $data = RequisitionCategory::where('company_id',$company_id)->get();
        $result = Vendor::where('requisition_categories_id',$request->requisition_categories_id)->get();
        return view('Requisition.vendorList',compact('data','result')); 
    }

    public function VendorEdit($id){
        $data = Vendor::find($id);
        return view('Requisition.editVendor',compact('data'));
    }

    public function vendorUpdate(Request $request,$id){
        $data = Vendor::find($id);
        $data->vendor_name = $request->vendor_name ?? $data->vendor_name;
        if ($request->hasFile('agreement_attachment')) {
            $file = $request->file('agreement_attachment');
            $extension = $file->getClientOriginalExtension();
            $pdfPath = $file->storeAs('pdfs', time() . '.' . $extension, 'public');
            $data->agreement_attachment = 'storage/' . $pdfPath;
        }        
        $data->save();
        return redirect()->route('vendorList')->with('success','Vendor Updated');
    }

    public function vendorDelete(Request $request){
        Vendor::destroy($request->vendors_id);
        return redirect()->route('vendorList')->with('success','Vendor Deleted Successfully');
    }
}
