<?php

namespace App\Http\Controllers\Requisition;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Requisition;
use App\Models\RequisitionCategory;
use Illuminate\Http\Request;

class requisitionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['requisitionList','approveRequisition']]);

    }

    public function catagoryList(){
        $company_id= auth()->user()->company_id;
        $data = RequisitionCategory::where('company_id',$company_id)->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Requisition Category',
                'data'=>$data
            ],200);
        }
    }

    public function productList($id){
       $data =  Product::where('requisition_categories_id',$id)->get();
       if(count($data) == 0){
            return response()->json([
                'message'=>'No data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Product List',
                'data'=>$data
            ],200);
        }
    }

    public function requisition(Request $request){
        $validator= Validator::make($request->all(), [
            'requisition_categories_id' => 'required|integer',
            'products_id'  => 'integer',
            'product_name' => 'required|string',
            'quantity'  => 'required|integer',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $company_id= auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id', $user_id)->value('emp_id');
        if($request->products_id == null){
            $products_id = 0;
        }else{
            $products_id = $request->products_id;
        }
        $data = new Requisition();
        $data->requisition_categories_id = $request->requisition_categories_id;
        $data->products_id = $products_id;
        $data->product_name = $request->product_name;
        $data->quantity = $request->quantity;
        $data->reason = $request->reason;
        $data->emp_id = $emp_id;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=>'Requisition Added',
            'data'=>$data
        ],201);
    }

    public function requisitionList(){
        $company_id= session('company_id');
        $user_id = session('id');
        $emp_id = Employee::where('id', $user_id)->value('emp_id');
        $data = Requisition::where('requisitions.company_id', $company_id)
        ->join('requisition_categories', 'requisition_categories.requisition_categories_id', '=', 'requisitions.requisition_categories_id')
        ->join('employees', 'employees.emp_id', '=', 'requisitions.emp_id')
        ->select('requisitions.*', 'requisition_categories.category_name', 'employees.name as employee_name')
        ->get();
        return view('Requisition.requisitionList',compact('data'));
    }

    public function approveRequisition(Request $request){
        $data = Requisition::find($request->requisitions_id);
        $data->status = $request->action;
        if($data->save()){
            if($request->action == "approved"){
                return redirect()->back()->with('success','Requisition Approved');
            }else{
                return redirect()->back()->with('error','Requisition Rejected');
            }
        }else{
            return redirect()->back()->with('error','Data Not Saved');
        }
        
    }

    public function requisitionListForUser(){
        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id', $user_id)->value('emp_id');
        $data = Requisition::where('emp_id', $emp_id)
                        ->join('requisition_categories', 'requisitions.requisition_categories_id', '=', 'requisition_categories.requisition_categories_id')
                        ->select('requisitions.*', 'requisition_categories.category_name as category_name')
                        ->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No Data Found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Requisition List',
                'data'=>$data
            ],200);
        }
    }

    public function requisitionDetails($id){
        // $user_id = auth()->user()->id;
        // $emp_id = Employee::where('id', $user_id)->value('emp_id');
        $data = Requisition::where('requisitions_id', $id)
                        ->join('requisition_categories', 'requisitions.requisition_categories_id', '=', 'requisition_categories.requisition_categories_id')
                        ->select('requisitions.*', 'requisition_categories.category_name as category_name')
                        ->first();
        if(!$data){
            return response()->json([
                'message'=>'No Data Found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Requisition Details',
                'data'=>$data
            ],200);
        }
    } 
}
