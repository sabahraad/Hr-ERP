<?php

namespace App\Http\Controllers\Requisition;

use App\Http\Controllers\Controller;
use App\Models\RequisitionCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategory(Request $request){
        $company_id = Session('company_id');
        $data = new RequisitionCategory();
        $data->category_name = $request->category_name;
        $data->category_description = $request->category_description;
        $data->company_id = $company_id;
        $data->save();
        return redirect()->back()->with('success','Category Created Successfully');
    }

    public function categoryList(){
        $company_id = Session('company_id');
        $data = RequisitionCategory::where('company_id',$company_id)->get();
        return view('Requisition.category',compact('data'));
    }

    public function categoryEdit($id){
        $data = RequisitionCategory::find($id);
        return view('Requisition.editCategory',compact('data'));
    }

    public function categoryUpdate(Request $request,$id){
        $data = RequisitionCategory::find($id);
        $data->category_name = $request->category_name;
        $data->category_description = $request->category_description;
        $data->save();
        return redirect()->route('super-admin.categoryList')->with('success','Category Updated');
    }

    public function categoryDelete(Request $request){
        RequisitionCategory::destroy($request->requisition_categories_id);
        return redirect()->route('super-admin.categoryList')->with('success','Category Deleted Successfully');
    }
}
