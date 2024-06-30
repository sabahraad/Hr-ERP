<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Models\Salary;
use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Mockdetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\TimelineSetting;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
   
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','forgetPassword']]);
    }

    public function login(Request $request){

    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'error' => 'Please Enter Valid Email & Password'
            ], 401);
        }
        
        return $this->createNewToken($token);
    }
   
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string|unique:companies',
            'address' => 'required|string',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'companyDetails' => 'required',
            'contactNumber' => 'required|regex:/^\d{10,}$/',
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6'    
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
      
        // Create the company
        $company = $this->createCompany($request);

        // Create the user
        $user = $this->createUser($request, $company);

        // Create the department
        $department = $this->createDepartment($company);

        // Create the designation
        $designation = $this->createDesignation($department);

        // Create the employee
        $employee = $this->createEmployee($request, $user, $department, $designation, $company);

        // Create the salary record
        $this->createSalary($employee, $company);
        
        return response()->json([
            'message' => 'Company Successfully Registered',
        ],Response::HTTP_CREATED);
    }

    private function createCompany($request)
    {
        $imageName = time() . '.' . $request->logo->extension();
        $request->logo->move(public_path('images'), $imageName);
        $imagePath = 'images/' . $imageName;
    
        $company = new Company();
        $company->companyName = $request->companyName;
        $company->logo = $imagePath;
        $company->address = $request->address;
        $company->contactNumber = $request->contactNumber;
        $company->companyDetails = $request->companyDetails;
        $company->save();
    
        return $company;
    }
    
    private function createUser($request, $company)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->company_id = $company->id;
        $user->role = 2;
        $user->save();
    
        return $user;
    }
    
    private function createDepartment($company)
    {
        $department = new Department();
        $department->deptTitle = 'People Care';
        $department->company_id = $company->id;
        $department->save();
    
        return $department;
    }
    
    private function createDesignation($department)
    {
        $designation = new Designation();
        $designation->desigTitle = 'HR';
        $designation->dept_id = $department->id;
        $designation->save();
    
        return $designation;
    }
    
    private function createEmployee($request, $user, $department, $designation, $company)
    {
        $employee = new Employee();
        $employee->id = $user->id;
        $employee->officeEmployeeID = $request->officeEmployeeID;
        $employee->name = $request->name;
        $employee->dept_id = $department->id;
        $employee->designation_id = $designation->id;
        $employee->company_id = $company->id;
        $employee->save();
    
        return $employee;
    }
    
    private function createSalary($employee, $company)
    {
        $salary = new Salary();
        $salary->salary = 0;
        $salary->emp_id = $employee->id;
        $salary->company_id = $company->id;
        $salary->save();
    }

 
    public function logout() {
        auth()->logout();
        return response()->json([
            'message' => 'User successfully signed out'
        ],Response::HTTP_OK);
    }
   
    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }
  
    public function userProfile() {
        $id = auth()->user()->id;
        $company_id = auth()->user()->company_id;
        $emp_details = Employee::where('employees.company_id',$company_id)->where('employees.id',$id)
                        ->join("users", "users.id", "=", "employees.id")
                        ->join("companies","companies.company_id","=","employees.company_id")
                        ->join("departments","departments.dept_id","=","employees.dept_id")
                        ->join("designations","designations.designation_id","=","employees.designation_id")
                        ->get(['employees.*', 'users.email','departments.deptTitle','designations.desigTitle','companies.companyName']);
        
        $emp_id = Employee::where('id',$id)->value('emp_id');

        $fatch_time = TimelineSetting::where('emp_id',$emp_id)->value('fetch_time');
        if(!$fatch_time){
            $timeLine = false;
        }else{
            $timeLine = true;
        }

        return response()->json([
            'message' => 'User Deatils',
            'data' => Auth()->user(),
            'emp_details'=> $emp_details,
            'timeLine' => $timeLine,
            'fatch_time' => $fatch_time
        ],Response::HTTP_OK);
            
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => JWTAuth::manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['exp'],

            'expires_in' => Auth()->factory()->getTTL(),
            'user' => auth()->user()
        ]);
    }

    public function saveMockPersonDetails(Request $request){
        $data = new Mockdetails();
        $data->emp_id = $request->emp_id;
        $data->emp_name = $request->name;
        $data->email = $request->email;
        $data->company_id = $request->company_id;
        $data->save();
        return response()->json([
            'message'=>'Mock Person Data Has Been Stored Successfully',
            'data'=>$data
        ],201);
    }

    public function showMockPersonDetails(){
        $company_id = auth()->user()->company_id;
        $data = Mockdetails::where('company_id',$company_id)->get();
        return response()->json([
            'message'=>'List of People who have try to Mock',
            'data'=>$data
        ],200);
    }

    
    public function passwordChange(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:6'    
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        // Retrieve user by email
        $user = User::where('email', $request->email)->first();
        if ($user) {
            // Compare hashed passwords
            if (Hash::check($request->old_password, $user->password)) {
                // Update user's password
                $user->password = bcrypt($request->password);
                $user->save();
                //destroy old token
                return response()->json([
                    'message' => 'Authentication successful'
                ], 200);
            }
        }else{
            // Authentication failed
            return response()->json([
                'message' => 'Old Password Does Not Matched',
            ], 400);
        }

        
    }

   
}