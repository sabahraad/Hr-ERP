<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Expenses;
use App\Models\ExpensesCatagory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class expensesController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createExpenses(Request $request){
        $validator = Validator::make($request->all(), [
            'description' => 'string',
            'catagory'=>'required|string',
            'expenses_catagories_id' => 'required|integer',
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
        $data->catagory = $request->catagory;
        $data->expenses_catagories_id = $request->expenses_catagories_id;
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
            'catagory'=>'required|string',
            'expenses_catagories_id' => 'required|integer',
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
        $data->total_amount = $request->total_amount ?? $data->total_amount;
        $data->catagory = $request->catagory?? $data->catagory;
        $data->expenses_catagories_id  = $request->expenses_catagories_id ?? $data->expenses_catagories_id ;
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
        $data = Expenses::where('emp_id',$emp_id)
                        ->orderBy('created_at','desc')
                        ->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No data available',
                'data'=>$data
    
            ],200);
        }else{
            return response()->json([
                'message'=>'Expenses List',
                'data'=>$data
    
            ],200);
        }
    }

    public function deleteExpenses($id){
        $data = Expenses::where('expenses_id',$id)->value('status');
        if($data == 'pending'){
            Expenses::destroy($id);
            return response()->json([
            ],204);
        }else{
            return response()->json([
                'message'=>'You can not delete expense deatils bacause it is already rejected or approved'
            ],200);
        }
        
    }

    public function allExpensesList(){
        $company_id = auth()->user()->company_id;
        $data = Expenses::where('expenses.company_id',$company_id)
                        ->join("employees","employees.emp_id","=","expenses.emp_id")
                        ->join("expenses_catagories","expenses.expenses_catagories_id","=","expenses_catagories.expenses_catagories_id")
                        ->get(['employees.*','expenses.*','expenses_catagories.catagory']);
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
            'status' => 'required|integer|in:1,2,0'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $approved = 1;
        $rejected = 2;
        $pending = 0;
        if($request->status == $approved){
            Expenses::where('expenses_id', $request->expenses_id)->update(['status' => 'approved']);
            return response()->json([
                'message'=>'Expense Approved Successfully',
                'data'=>$approved,
            ],200);
        }elseif($request->status == $rejected){
            Expenses::where('expenses_id', $request->expenses_id)->update(['status' => 'rejected']);
            return response()->json([
                'message'=>'Expense rejected',
                'data'=>$rejected,
            ],200);
        }elseif($request->status == $pending){
            Expenses::where('expenses_id', $request->expenses_id)->update(['status' => 'pending']);
            return response()->json([
                'message'=>'Expense pending',
                'data'=>$pending
            ],200);
        }else{
            return response()->json([
                'message'=>'Something Went Wrong'
            ],400);
        }
    }

    public function catagoryList(){
        $company_id = auth()->user()->company_id;
        $data = ExpensesCatagory::where('company_id',$company_id)->get();
        if(empty($data)){
            return response()->json([
                'message'=>'No Catagory is set yet',
                'data'=>$data

            ],200);
        }else{
            return response()->json([
                'message'=>'Catagory List',
                'data'=>$data

            ],200);
        }
    }

    public function expenseDetails($id){
        $data = Expenses::where('expenses.expenses_id',$id)
                        ->join("employees","employees.emp_id","=","expenses.emp_id")
                        ->join("expenses_catagories","expenses.expenses_catagories_id","=","expenses_catagories.expenses_catagories_id")
                        ->get(['employees.*','expenses.*','expenses_catagories.catagory']);
        if(count($data) == 0){
            return response()->json([
                'message'=>'No Data Found',
                'data'=>$data
            ],404);
        }else{
            return response()->json([
                'message'=>'Expenses Details',
                'data'=>$data
            ],200);
        }
    }

    public function expenseReportDetails(Request $request){
        $date = $request->date_range;
        $dateParts = explode(' - ', $date);
        $startDate = $dateParts[0];
        $endDate = $dateParts[1];
        $company_id = auth()->user()->company_id;
        $result =Employee::select(
            'employees.emp_id',
            'employees.name',
            'departments.deptTitle as department',
            'designations.desigTitle as designation',
            DB::raw('(SELECT SUM(total_amount) FROM expenses WHERE emp_id = employees.emp_id AND status = "approved" AND created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'") as total_amount_sum')
        )
        ->join('departments', 'employees.dept_id', '=', 'departments.dept_id')
        ->join('designations', 'employees.designation_id', '=', 'designations.designation_id')
        ->where('employees.company_id', $company_id)
        ->havingRaw('total_amount_sum IS NOT NULL')
        ->get();
        return response()->json([
            'message'=> 'Expense Report Details',
            'data'=>$result,
            'startDate'=>$startDate,
            'endDate'=>$endDate
        ],200);
    }
}
