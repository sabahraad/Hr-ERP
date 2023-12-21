<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Expenses;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class expensesController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createExpenses(Request $request){
        $validator = Validator::make($request->all(), [
            'description' => 'string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total_amount' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'attachment' => 'mimes:jpg,jpeg,png,gif,svg,pdf,xlsx,xls'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $user_id = auth()->user()->id;
        $company_id = auth()->user()->company_id;
        $emp_id = Employee::where('id', $user_id)->value('emp_id');
        $data = new Expenses();
        $data->description = $request->description;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->total_amount = $request->total_amount;
        if($request->has('attachment')){
            
            $extension = $request->attachment->getClientOriginalExtension();
            if ($extension === 'pdf') {
                $pdfPath = $request->attachment->storeAs('pdfs', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$pdfPath;
            }
        
            if (in_array($extension, ['jpg','jpeg', 'png', 'gif', 'svg'])) {
                    $imagePath = $request->attachment->storeAs('images', time() . '.' . $extension, 'public');
                    $data->attachment = 'storage/'.$imagePath;
            }
        
            if (in_array($extension, ['xlsx', 'xls'])) {
                $excelPath = $request->attachment->storeAs('excels', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$excelPath;
            }
        }
        $data->emp_id = $emp_id;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=> 'Expense Added Successfully',
            'data'=>$data
        ],201);
    }

    public function editExpenses(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'description' => 'string',
            'start_date' => 'date',
            'end_date' => 'date',
            'total_amount' => 'regex:/^\d{1,8}(\.\d{1,2})?$/',
            'attachment' => 'mimes:jpg,jpeg,png,gif,svg,pdf,xlsx,xls'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->user()->id;
        $company_id = auth()->user()->company_id;
        $emp_id = Employee::where('id', $user_id)->value('emp_id');
        $data = Expenses::find($id);
        $data->description = $request->description ?? $data->description;
        $data->start_date = $request->start_date ?? $data->start_date;
        $data->end_date = $request->end_date ??  $data->end_date;
        $data->total_amount = $request->total_amount ?? $data->total_amount;
        if($request->has('attachment')){
            
            $extension = $request->attachment->getClientOriginalExtension();
            if ($extension === 'pdf') {
                $pdfPath = $request->attachment->storeAs('pdfs', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$pdfPath;
            }
        
            if (in_array($extension, ['jpg','jpeg', 'png', 'gif', 'svg'])) {
                    $imagePath = $request->attachment->storeAs('images', time() . '.' . $extension, 'public');
                    $data->attachment = 'storage/'.$imagePath;
            }
        
            if (in_array($extension, ['xlsx', 'xls'])) {
                $excelPath = $request->attachment->storeAs('excels', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$excelPath;
            }
        }
        $data->emp_id = $emp_id;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message'=> 'Expense deatails updated successfully',
            'data'=>$data
        ],200);
    }

    public function expensesList(){
        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id', $user_id)->value('emp_id');
        $data = Expenses::where('emp_id',$emp_id)->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No data available',
                'data'=>$data
    
            ],404);
        }else{
            return response()->json([
                'message'=>'Expenses List',
                'data'=>$data
    
            ],200);
        }
    }

    public function deleteExpenses($id){
        Expenses::destroy($id);
        return response()->json([
        ],204);
    }

    public function allExpensesList(){
        $company_id = auth()->user()->company_id;
        $data = Expenses::where('company_id',$company_id)->get();
        if(count($data) == 0 ){
            return response()->json([
                'message'=>'No data available',
                'data'=>$data
            ],404);
        }
        return response()->json([
            'message'=>'All Expenses List',
            'data'=>$data

        ],200);
    }

    public function approveExpense(Request $request){
        $validator = Validator::make($request->all(), [
            'expenses_id' => 'required|integer',
            'status' => 'required|integer|in:1,2'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $approved = 1;
        $rejected = 2;
        if($request->status == $approved){
            Expenses::where('expenses_id', $request->expenses_id)->update(['status' => 'approved']);
            $data = Expenses::find($request->expenses_id);
            return response()->json([
                'message'=>'Expense Approved Successfully',
                'data'=>$data,
            ],200);
        }elseif($request->status == $rejected){
            Expenses::where('expenses_id', $request->expenses_id)->update(['status' => 'rejected']);
            $data = Expenses::find($request->expenses_id);
            return response()->json([
                'message'=>'Expense rejected',
                'data'=>$data,
            ],200);
        }else{
            return response()->json([
                'message'=>'Something Went Wrong'
            ],400);
        }

    }
}