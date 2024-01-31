<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payslip;
use App\Models\PreviousSalaryHistroy;
use App\Models\Salary;
use App\Models\SalarySetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function employeeSalaryDetails($id){
        $data =Salary::where('salaries.emp_id', $id)
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
                'message'=>'Salary Details',
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

    public function salaryList(){
        $company_id = auth()->user()->company_id;
        $data = Salary::where('company_id',$company_id)->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No salary list found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Salary list',
                'data'=>$data
            ],200);
        }
    }

    public function createPayslip(Request $request){
        $month = $request->month;
        $year = $request->year;
        $company_id = auth()->user()->company_id;
        $salary = Salary::where('company_id',$company_id)->get();
        if(count($salary) == 0){
            return response()->json([
                'message'=>'Please set salary for employee first'
            ],200);
        }
        foreach($salary as $sal){
            if (!$this->payslipExists($sal->emp_id, $sal->company_id,$month,$year)) {
                $data = new Payslip();
                $data->salary = $sal->salary;
                $data->month = $request->month;
                $data->year = $request->year;
                $data->emp_id = $sal->emp_id;
                $data->company_id = $sal->company_id;
                $data->save();
            }
        }
        $payslip = Payslip::where('company_id',$company_id)
                            ->where('month',$request->month)
                            ->where('year',$request->year)
                            ->get();

        return response()->json([
            'message'=>'Payslip List for'.' '.$request->month,
            'data'=>$payslip
        ],201);
    }

    private function payslipExists($empId, $companyId,$month,$year)
    {
        return Payslip::where('emp_id', $empId)
            ->where('company_id', $companyId)
            ->where('month', $month)
            ->where('year', $year)
            ->exists();
    }

    public function adjustPayslip(Request $request,$id){
        $data = Payslip::find($id);
        $data->deducted_amount = $request->deducted_amount;
        $data->adjustment_reason = $request->adjustment_reason;
        $data->save();
        return response()->json([
            'message'=>'Payslip adjusted successfully',
            'data'=>$data
        ],200);
    }

    public function employeeSalaryHistory($id){
        $data = PreviousSalaryHistroy::where('emp_id',$id)->get();
        if(count($data)==0){
            return response()->json([
                'message'=>'no data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Previous Salary History',
                'data'=>$data
            ],200);
        }
    }

    public function payslipList(){
        $user_id = auth()->user()->id;
        $emp_id = Employee::where('id',$user_id)->value('emp_id');
        $data = Payslip::where('emp_id',$emp_id)->get();
        if(count($data) == 0){
            return response()->json([
                'message'=>'No data found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Payslip List',
                'data'=>$data
            ],200);
        }
    }

    public function payslipDetails($id){
        $company_id = auth()->user()->company_id;
        $data = Payslip::find($id);
        $salary_setting = SalarySetting::where('company_id',$company_id)->first();
        $salaryComponents = $salary_setting->components;
        $deducted_amount = $data->deducted_amount;
        $salary = $data->salary; 
        $adjustment_reason = $data->adjustment_reason;
        $salaryDistribution = [];
        foreach ($salaryComponents as $component) {
            $componentName = $component['name'];
            $componentPercentage = $component['percentage'];
            $componentAmount = ($componentPercentage / 100) * $salary;
            $salaryDistribution[$componentName] =  [
                'amount' => $componentAmount,
                'percentage' => $componentPercentage,
            ];
        }

        $employeeDetails = Employee::where('employees.emp_id', $data->emp_id)
                                    ->join('departments', 'departments.dept_id', '=', 'employees.dept_id')
                                    ->join('designations', 'designations.designation_id', '=', 'employees.designation_id')
                                    ->get(['employees.*','departments.deptTitle', 'designations.desigTitle']);

        return response()->json([
            'message'=>'Payslip Details',
            'data'=>
                [
                    'employee details' => $employeeDetails,
                    'salaryDistribution' => $salaryDistribution,
                    'Salary'=>$salary,
                    'deducted_amount' => $deducted_amount,
                    'adjustment_reason'=> $adjustment_reason
                ]
            ],200);
    }

}
