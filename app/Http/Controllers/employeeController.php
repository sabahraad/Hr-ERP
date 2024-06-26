<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Carbon\Exceptions\InvalidFormatException;


class employeeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addEmployee(Request $request){

        $validator = Validator::make($request->all(), [
            'officeEmployeeID' => 'string|unique:employees,officeEmployeeID',
            'name' => 'required|string|between:2,100',
            'gender' => 'string',
            'dob' => 'string',
            'salary'=>'required|integer',
            'joining_date' => 'required|date',
            'dept_id' => 'required|integer',
            'designation_id' => 'required|integer',
            'phone_number' => 'digits:11',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'string',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6' 
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company_id = auth()->user()->company_id;
        // Parse joining_date and dob with flexible format handling
        $joining_date = $request->joining_date ? $this->parseDate($request->joining_date) : null;
        $dob = $request->dob ? $this->parseDate($request->dob) : null;
        $user = $this->createUser($request, $company_id);
        $user_id = $user->id;

        $employee = $this->createEmployee($request, $user_id, $company_id, $joining_date, $dob);

        if ($request->has('salary')) {
            $this->createSalary($request->salary, $employee->emp_id, $company_id, $joining_date);
        }
        return response()->json(['message' => 'Employee Added Successfully', 'data' => $employee], 201);
    }

    private function createUser($request, $company_id)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->company_id = $company_id;
        $user->save();

        return $user;
    }

    private function createEmployee($request, $user_id, $company_id, $joining_date, $dob)
    {
        $employee = new Employee();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $employee->image = 'images/' . $imageName;
        }

        $employee->id = $user_id;
        $employee->officeEmployeeID = $request->officeEmployeeID;
        $employee->name = $request->name;
        $employee->gender = $request->gender;
        $employee->dob = $dob;
        $employee->joining_date = $joining_date;
        $employee->phone_number = $request->phone_number;
        $employee->dept_id = $request->dept_id;
        $employee->designation_id = $request->designation_id;
        $employee->status = $request->status;
        $employee->company_id = $company_id;
        $employee->save();

        return $employee;
    }

    private function createSalary($salary, $emp_id, $company_id, $joining_date)
    {
        $sal = new Salary();
        $sal->salary = $salary;
        $sal->joining_date = $joining_date;
        $sal->last_increment_date = $joining_date;
        $sal->emp_id = $emp_id;
        $sal->company_id = $company_id;
        $sal->save();
    }

    public function employeeList(){

        $company_id= auth()->user()->company_id;
        $data = Employee::where('employees.company_id',$company_id)
                ->join("users", "users.id", "=", "employees.id")
                ->join("departments","departments.dept_id","=","employees.dept_id")
                ->join("designations","designations.designation_id","=","employees.designation_id")
                ->orderBy('employees.dept_id')
                ->get(['employees.*', 'users.email','departments.deptTitle','designations.desigTitle'])
                ->groupBy('dept_id');

        $result = [];

        foreach ($data as $deptId => $deptEmployees) {
            $deptInfo = [
                "dept_id" => $deptId,
                "deptTitle" => $deptEmployees->first()->deptTitle, 
                "employees" => $deptEmployees->toArray(),
            ];

            $result[] = $deptInfo;
        }
        return response()->json([
            'message'=> 'Employee List',
            'data'=>$result
        ],200);

    }

    public function empList(){
        $company_id = auth()->user()->company_id;
        $data = Employee::where('company_id',$company_id)->get();
        return response()->json([
            'message'=>'employee list',
            'data'=>$data
        ],200);
    }

    public function employeeListForAdminPanel(){
        $company_id= auth()->user()->company_id;
        $data = Employee::where('employees.company_id',$company_id)
                ->join("users", "users.id", "=", "employees.id")
                ->join("departments","departments.dept_id","=","employees.dept_id")
                ->join("designations","designations.designation_id","=","employees.designation_id")
                ->orderBy('employees.updated_at','desc')
                ->get(['employees.*', 'users.email','departments.deptTitle','designations.desigTitle']);
        return response()->json([
            'message'=> 'Employee List',
            'data'=>$data
        ],200);
    }

    public function updateEmployee(Request $request,$id){
        $user_id = Employee::where('emp_id',$id)->value('id');
        
        $validator = Validator::make($request->all(), [
            'officeEmployeeID' => 'string',
            'name' => 'required|string|between:2,100',
            'gender' => 'string',
            'dob' => 'date',
            'salary' => 'integer',
            'joining_date' =>'date',
            'dept_id' => 'required|integer',
            'phone_number' => 'digits:11',
            'designation_id' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'string',
            'email' => 'required|string|email|max:100|unique:users,email,'.$user_id.',id',
            'password' => 'string|confirmed|min:6' 
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $company_id = auth()->user()->company_id;
        $userInfo = $this->updateUser($request, $user_id, $company_id);

        if (!$userInfo) {
            return response()->json(['message' => 'User Not Found'], 404);
        }

        $employee = $this->updateEmployeeData($request, $id, $company_id, $user_id);

        if (!$employee) {
            return response()->json(['message' => 'Employee Not Found'], 404);
        }

        if ($request->has('salary')) {
            $this->updateSalary($request->salary, $employee->emp_id, $company_id, $request->joining_date);
        }

        return response()->json(['message' => 'Employee Updated Successfully', 'data' => $employee], 200);
    }

    private function updateUser($request, $user_id, $company_id)
    {
        $user = User::where('id', $user_id)->where('company_id', $company_id)->first();

        if ($user) {
            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;

            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();
        }

        return $user;
    }

    private function updateEmployeeData($request, $id, $company_id, $user_id)
    {
        $employee = Employee::find($id);
        // Parse joining_date and dob with flexible format handling
        $joining_date = $request->joining_date ? $this->parseDate($request->joining_date) : null;
        $dob = $request->dob ? $this->parseDate($request->dob) : null;
        if ($employee) {
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $employee->image = 'images/' . $imageName;
            }

            $employee->id = $user_id ?? $employee->id;
            $employee->officeEmployeeID = $request->officeEmployeeID ?? $employee->officeEmployeeID;
            $employee->name = $request->name ?? $employee->name;
            $employee->gender = $request->gender ?? $employee->gender;
            $employee->dob = $dob ?? $employee->dob;
            $employee->joining_date = $joining_date ?? $employee->joining_date;
            $employee->phone_number = $request->phone_number ?? $employee->phone_number;
            $employee->dept_id = $request->dept_id ?? $employee->dept_id;
            $employee->designation_id = $request->designation_id ?? $employee->designation_id;
            $employee->status = $request->status ?? $employee->status;
            $employee->company_id = $company_id;
            $employee->save();
        }

        return $employee;
    }

    private function updateSalary($salary, $emp_id, $company_id, $joining_date)
    {
        $sal = Salary::where('emp_id', $emp_id)->first();
        $joining_date = Carbon::createFromFormat('d-m-Y', $joining_date)->format('Y-m-d');

        if ($sal) {
            $sal->salary = $salary ?? $sal->salary;
            $sal->joining_date = $joining_date ?? $sal->joining_date;
            $sal->company_id = $company_id;
        } else {
            $sal = new Salary();
            $sal->salary = $salary;
            $sal->joining_date = $joining_date;
            $sal->emp_id = $emp_id;
            $sal->company_id = $company_id;
        }

        $sal->save();
    }

    
    // Helper function to parse date with flexible formats
    private function parseDate($dateString)
    {
        if (!$dateString) {
            return null;
        }

        // List of possible formats to check against
        $formatsToCheck = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'Y/m/d', 'Ymd', 'dmY'];

        // Try to create a Carbon instance from each format until successful
        foreach ($formatsToCheck as $format) {
            try {
                $carbonDate = Carbon::createFromFormat($format, $dateString);
                if ($carbonDate && $carbonDate->format($format) === $dateString) {
                    return $carbonDate->format('Y-m-d');
                }
            } catch (InvalidFormatException $e) {
                // Continue to the next format if current one fails
                continue;
            }
        }

        // If none of the formats matched, return null or handle the error as needed
        return null;
    }

    public function employeeEditApp(Request $request,$id){
        
        $validator = Validator::make($request->all(), [
            'name' => 'string|between:2,100',
            'gender' => 'string',
            'dob' => 'string',
            'phone_number' => 'digits:11',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $user_id = Employee::where('emp_id',$id)->value('id');
        if(!$user_id){
            return response()->json([
                'message' => 'User Not Found',
            ],404);
        }
        $user = User::find($user_id);
        if(!$user){
            return response()->json([
                'message' => 'User Not Found',
            ],404);
        }
        $user->name = $request->name ?? $user->name;
        $user->save();

        $employee = Employee::find($id);
        
        if(!$employee){
            return response()->json([
                'message' => 'Employee Not Found',
            ],404);
        }

        $employee->dob = $request->dob ?? $employee->dob;
        $employee->gender = $request->gender ?? $employee->gender;
        $employee->name = $request->name ?? $employee->name;
        $employee->phone_number = $request->phone_number ?? $employee->phone_number;

        if($request->hasFile('image')){
            $imageName =  time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
            $employee->image = $imagePath;
        }

        $employee->save();

        return response()->json([
            'message'=> 'Information Updated',
            'data'=>$employee
        ],200);
    }

    public function deleteEmployee($id){
        $company_id= auth()->user()->company_id;
        $user_id = Employee::where('emp_id',$id)->where('company_id',$company_id)->value('id');
        if(!$user_id){
            return response()->json([
                'message' => 'Something Went Wrong'
            ],422);
        }
        User::where('id',$user_id)->where('company_id',$company_id)->delete();
        Employee::where('emp_id',$id)->where('company_id',$company_id)->delete();
        return response()->json([
            'message' => 'Employee deleted successfully'
        ]);
    }

    public function employeeDetails($id){
        $company_id= auth()->user()->company_id;
        $data = Employee::where('employees.emp_id', $id)
                        ->where('employees.company_id',$company_id)
                        ->join("users", "users.id", "=", "employees.id")
                        ->leftJoin("salaries", "salaries.emp_id", "=", "employees.emp_id")
                        ->get(['employees.*', 'users.email', 'salaries.salary']);
      
        if(count($data) == 0){
            return response()->json([
                'message'=>'No data found'
            ],404);
        }
        return response()->json([
            'message'=> 'Employee Details',
            'data'=>$data
        ],200);
    }

    public function uploadEmployees(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');

        try {
            // ... your existing code
    
            $import = new EmployeesImport();
            Excel::import($import, $file);
    
            // ... your existing code to return success response
        } catch (ValidationException $e) {
            $validationErrors = $e->errors();
    
            return response()->json([
                'error' => $validationErrors,
            ], 422);
        }

        // Excel::import(new EmployeesImport, $file);

        return response()->json([
            'message'=> 'Employee Added Successfully'
        ],201);
    }

}
