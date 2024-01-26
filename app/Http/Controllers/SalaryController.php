<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
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
        foreach($salary as $sal){
            if (!$this->payslipExists($sal->emp_id, $sal->company_id)) {
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
            'message'=>'Payslip List for'.$request->month,
            'data'=>$payslip
        ],201);
    }

    private function payslipExists($empId, $companyId)
    {
        return Payslip::where('emp_id', $empId)
            ->where('company_id', $companyId)
            ->where('month', now()->format('m'))
            ->where('year', now()->format('Y'))
            ->exists();
    }

    public function adjustPayslip(Request $request,$id){
        $data = Payslip::find($id);
        $data->salary = $request->salary;
        $data->adjustment_reason = $request->adjustment_reason;
        $data->save();
        return response()->json([
            'message'=>'Payslip adjusted successfully',
            'data'=>$data
        ],200);
    }

}
