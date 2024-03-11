<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
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

    public function employeeSalaryDetails(){
        $company_id = auth()->user()->company_id;

        $data = Salary::where('salaries.company_id', $company_id)
                        ->join('employees', function ($join) use ($company_id) {
                            $join->on('salaries.emp_id', '=', 'employees.emp_id')
                                ->where('salaries.company_id', '=', $company_id)
                                ->where('employees.company_id', '=', $company_id);
                        })
                        ->join('users', 'employees.id', '=', 'users.id')
                        ->select('salaries.*', 'employees.*','users.email')
                        ->get();

        if(count($data) == 0){
            return response()->json([
                'message'=>'No Salary Details Found',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'message'=>'Salary Details',
                'data'=>$data
            ],200);
        }
    }

    public function individualEmployeeSalaryDetails($id){
        $data = Salary::where('salaries.emp_id', $id)
                        ->join('employees', 'salaries.emp_id', '=', 'employees.emp_id')
                        ->join('users', 'users.id', '=', 'employees.id')
                        ->select('salaries.*', 'employees.*', 'users.email')
                        ->get();

        if(count($data) == 0){
            return response()->json([
                'message'=>'No Salary Details Found',
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
            'salary' => 'required|integer'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $increment_date = Carbon::now()->format('Y-m-d');
        $privouseSalaryDetails = Salary::find($id);

        $savePrivouseSalaryDetails = new PreviousSalaryHistroy();
        $savePrivouseSalaryDetails->salary = $privouseSalaryDetails->salary;
        $savePrivouseSalaryDetails->joining_date = $privouseSalaryDetails->joining_date;
        $savePrivouseSalaryDetails->salary_update_date = $privouseSalaryDetails->last_increment_date;
        $savePrivouseSalaryDetails->emp_id = $privouseSalaryDetails->emp_id;
        $savePrivouseSalaryDetails->company_id = $privouseSalaryDetails->company_id;
        $savePrivouseSalaryDetails->save();

        $privouseSalaryDetails->salary = $request->salary;
        $privouseSalaryDetails->last_increment_date = $increment_date;
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
        if($request->adjustment_type == "addition"){
            $after_adjustment_salary = $data->salary + $request->adjusted_amount;
        }else{
            $after_adjustment_salary = $data->salary - $request->adjusted_amount;
        }
        $data->adjustment_type = $request->adjustment_type;
        $data->adjustment_reason = $request->adjustment_reason;
        $data->after_adjustment_salary = $after_adjustment_salary;
        $data->adjusted_amount = $request->adjusted_amount;
        $data->adjustment_reason = $request->adjustment_reason;
        $data->save();
        return response()->json([
            'message'=>'Payslip adjusted successfully',
            'data'=>$data
        ],200);
    }

    public function employeeSalaryHistory($id){
        $data = PreviousSalaryHistroy::where('previous_salary_histroys.emp_id', $id)
                                    ->join('employees', 'previous_salary_histroys.emp_id', '=', 'employees.emp_id')
                                    ->select('previous_salary_histroys.*', 'employees.*')
                                    ->orderBy('previous_salary_histroys.salary_update_date', 'desc') 
                                    ->get();
        if(count($data)==0){
            return response()->json([
                'message'=>'no data found',
                'data'=>$data
            ],404);
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
        $salary = $data->salary; 
        $adjustment_type = $data->adjustment_type;
        $adjusted_amount = $data->adjusted_amount;
        $after_adjustment_salary = $data->after_adjustment_salary;
        $adjustment_reason = $data->adjustment_reason;
        $salaryDistribution = [];
        foreach ($salaryComponents as $component) {
            $componentName = $component['name'];
            $componentPercentage = $component['percentage'];
            $componentAmount = ($componentPercentage / 100) * $salary;
            $details =  [
                'componentName' =>$componentName,
                'amount' => $componentAmount,
                'percentage' => $componentPercentage,
            ];
            $salaryDistribution[]= $details;
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
                    'adjustment_type' => $adjustment_type,
                    'adjusted_amount' => $adjusted_amount,
                    'Payable Amount' => $after_adjustment_salary,
                    'adjustment_reason'=> $adjustment_reason
                ]
            ],200);
    }

    public function payslipListCompanyWise($month,$year){
        $company_id = auth()->user()->company_id;
        $data = Payslip::where('payslips.company_id', $company_id)
                        ->where('payslips.month', $month)
                        ->where('payslips.year', $year)
                        ->join('employees', 'payslips.emp_id', '=', 'employees.emp_id')
                        ->join('departments', 'departments.dept_id', '=', 'employees.dept_id')
                        ->join('designations', 'designations.designation_id', '=', 'employees.designation_id')
                        ->where('employees.company_id', $company_id)
                        ->select(
                            'employees.*',
                            'departments.deptTitle',
                            'designations.desigTitle',
                            'payslips.salary', 
                            'payslips.adjustment_type',
                            'payslips.after_adjustment_salary',
                            'payslips.adjustment_reason',
                            'payslips.adjusted_amount',
                            'payslips.status as payslips_status',
                            'payslips.month',
                            'payslips.year',
                            'payslips.payslips_id',
                        )
                        ->get();
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


}
