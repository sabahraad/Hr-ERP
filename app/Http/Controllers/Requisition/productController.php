<?php

namespace App\Http\Controllers\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RequisitionCategory;
use Illuminate\Http\Request;

class productController extends Controller
{
    public function createProduct(Request $request){
        $data = new Product();
        $data->product_name = $request->product_name;
        $data->product_description = $request->product_description;
        $data->requisition_categories_id = $request->requisition_categories_id;
        $data->save();
        return redirect()->route('productList')->with('success','Product Created Successfully');
    }

    public function productList(){
        $company_id = Session('company_id');
        $data = RequisitionCategory::where('company_id',$company_id)->get();
        $result = Product::with('requisitionCategory')
                        ->whereHas('requisitionCategory', function ($query) use ($company_id) {
                            $query->where('company_id', $company_id);
                        })
                        ->get();
        return view('Requisition.product',compact('data','result'));
    }

    public function findProduct(Request $request){
        $company_id = Session('company_id');
        $data = RequisitionCategory::where('company_id',$company_id)->get();
        $result = Product::where('requisition_categories_id',$request->requisition_categories_id)->get();
        return view('Requisition.product',compact('data','result')); 
    }

    public function productEdit($id){
        $data = Product::find($id);
        return view('Requisition.editProduct',compact('data'));
    }

    public function productUpdate(Request $request,$id){
        $data = Product::find($id);
        $data->product_name = $request->product_name ?? $data->product_name;
        $data->product_description = $request->product_description ?? $data->product_description;
        // $data->requisition_categories_id = $data->requisition_categories_id ?? $data->requisition_categories_id;
        $data->save();
        return redirect()->route('productList')->with('success','Category Updated');
    }

    public function productDelete(Request $request){
        Product::destroy($request->products_id);
        return redirect()->route('productList')->with('success','Category Deleted Successfully');
    }
}
