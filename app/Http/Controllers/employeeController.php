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

        $company_id= auth()->user()->company_id;
        $joining_date = Carbon::createFromFormat('d-m-Y', $request->joining_date)->format('Y-m-d');

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->company_id = $company_id;
        $user->save();

        $user_id = User::where('email',$request->email)->value('id');

        $data=new Employee();

        if($request->hasFile('image')){
            $imageName =  time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
            $data->image = $imagePath;
        }
               
        // $user_id = auth()->user()->id;
        $data->id = $user_id;
        $data->officeEmployeeID = $request->officeEmployeeID;
        $data->name = $request->name;
        $data->gender = $request->gender;
        $data->dob = $request->dob;
        $data->joining_date = $joining_date;
        $data->phone_number = $request->phone_number;
        $data->dept_id = $request->dept_id;
        $data->designation_id = $request->designation_id;
        $data->status = $request->status;
        $data->company_id = $company_id;
        
        if($data->save()){
            if($request->has('salary')){
                $sal = new Salary();
                $sal->salary = $request->salary;
                $sal->joining_date = $joining_date; 
                $sal->last_increment_date = $joining_date; 
                $sal->emp_id = $data->emp_id; 
                $sal->company_id = $data->company_id; 
                $sal->save();
            }
        }else{
            return response()->json([
                'message' => 'something Went Wrong'
            ],500);
        }

        return response()->json([
            'message' => 'Employee Added Successful',
            'data' => $data
        ],201);
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
        $userInfo = User::where('id',$user_id)->first();
        $company_id= auth()->user()->company_id;
        $joining_date = $request->joining_date;
        if(!$userInfo){
            return response()->json([
                'message' => 'User Not Found',
            ],404);
        }
        $userInfo->name = $request->name ?? $userInfo->name;
        $userInfo->email = $request->email ?? $userInfo->email;
        if($request->has('password')){
            $userInfo->password = bcrypt($request->password);
            $userInfo->company_id = $company_id;
        }
        $userInfo->save();

        $data=Employee::find($id);

        if(!$data){
            return response()->json([
                'message' => 'Employee Not Found',
            ],404);
        }

        if($request->hasFile('image')){
            $imageName =  time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
            $data->image = $imagePath;
        }

        // $user_id = Employee::where('emp_id',$id)->value('id');
        $data->id = $user_id ?? $data->id;
        $data->officeEmployeeID = $request->officeEmployeeID ?? $data->officeEmployeeID;
        $data->name = $request->name ?? $data->name;
        $data->gender = $request->gender ?? $data->gender;
        $data->dob = $request->dob ?? $data->dob;
        $data->joining_date = $joining_date ?? $data->joining_date;
        $data->phone_number = $request->phone_number ?? $data->phone_number;
        $data->dept_id = $request->dept_id ?? $data->dept_id;
        $data->designation_id = $request->designation_id ?? $data->designation_id;
        $data->status = $request->status ?? $data->status;
        $data->company_id = $company_id;
        $data->save();

        if($request->has('salary')){
            $joiningDate = date('Y-m-d', strtotime($joining_date));
            $sal = Salary::where('emp_id',$data->emp_id)->first();
            if($sal){
                $sal->salary = $request->salary ?? $sal->salary;
                $sal->joining_date = $joiningDate ?? $sal->joining_date; 
                $sal->save();
            }else{
                $sal = new Salary();
                $sal->salary = $request->salary;
                $sal->joining_date = $joiningDate; 
                $sal->emp_id = $data->emp_id; 
                $sal->company_id = $data->company_id; 
                $sal->save();
            }
        }

        return response()->json([
            'message' => 'Employee Updated Successful',
            'data' => $data
        ],200);
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
        $user_id = Employee::where('emp_id',$id)->value('id');
        User::where('id',$user_id)->delete();
        Employee::where('emp_id',$id)->delete();
        return response()->json([
            'message' => 'Employee deleted successfully'
        ]);
    }

    public function employeeDetails($id){
        $data = Employee::where('employees.emp_id', $id)
                        ->join("users", "users.id", "=", "employees.id")
                        ->leftJoin("salaries", "salaries.emp_id", "=", "employees.emp_id")
                        ->get(['employees.*', 'users.email', 'salaries.salary']);
        // $data = Employee::where('employees.emp_id',$id)
        //         ->join("users", "users.id", "=", "employees.id")
        //         ->join("salaries","salaries.emp_id","=","employees.emp_id")
        //         ->get(['employees.*', 'users.email','salaries.salary']);//
        if(!$data){
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
