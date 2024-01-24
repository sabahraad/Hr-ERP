<?php

namespace App\Http\Controllers;

use App\Models\PreviousSalaryHistroy;
use App\Models\Salary;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function employeeSalaryDetails($id){
        $data = Salary::where('emp_id', $id)
                        ->join('employees', 'salaries.emp_id', '=', 'employees.emp_id')
                        ->select('salaries.*', 'employees.*')
                        ->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No Salary Details Found For This Employee',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'No Salary Details Found For This Employee',
                'data'=>$data
            ],200);
        }
    }

    public function changeEmployeeSalary(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'salary' => 'required|integer',
            'last_increment_date' => 'required|date'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $privouseSalaryDetails = Salary::find($id);

        $savePrivouseSalaryDetails = new PreviousSalaryHistroy();
        $savePrivouseSalaryDetails->salary = $privouseSalaryDetails->salary;
        $savePrivouseSalaryDetails->joining_date = $privouseSalaryDetails->joining_date;
        $savePrivouseSalaryDetails->salary_update_date = $privouseSalaryDetails->last_increment_date;
        $savePrivouseSalaryDetails->emp_id = $privouseSalaryDetails->emp_id;
        $savePrivouseSalaryDetails->company_id = $privouseSalaryDetails->company_id;
        $savePrivouseSalaryDetails->save();

        $privouseSalaryDetails->salary = $request->salary;
        $privouseSalaryDetails->last_increment_date = $request->last_increment_date;
        $privouseSalaryDetails->save();

        return response()->json([
            'message'=>'Employee Salary has been updated',
            'data'=>$privouseSalaryDetails
        ],200);
    }
}
