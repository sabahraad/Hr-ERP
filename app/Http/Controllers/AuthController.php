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
      //Create Company
        $imageName =  time() . '.' . $request->logo->extension();
        $request->logo->move(public_path('images'), $imageName);
        $imagePath = 'images/' . $imageName;

        $data = new Company();
        $data->companyName = $request->companyName;
        $data->logo = $imagePath;
        $data->address = $request->address;
        $data->contactNumber = $request->contactNumber;
        $data->companyDetails = $request->companyDetails;
        $data->save();


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->company_id = $data->company_id;
        $user->role = 2;
        $user->save();

        $dept = new Department();
        $dept->deptTitle = "People Care";
        $dept->company_id = $data->company_id;
        $dept->save();

        $desig = new Designation();
        $desig->desigTitle = "HR";
        $desig->dept_id = $dept->dept_id;
        $desig->save();

        $employee= new Employee();
        $employee->id = $user->id;
        $employee->officeEmployeeID = $request->officeEmployeeID;
        $employee->name = $request->name;
        $employee->dept_id = $dept->dept_id;
        $employee->designation_id = $desig->designation_id;
        $employee->company_id = $data->company_id;
        $employee->save();

        $sal = new Salary();
        $sal->salary = 000000;
        $sal->emp_id = $employee->emp_id; 
        $sal->company_id = $data->company_id; 
        $sal->save();
        
        return response()->json([
            'message' => 'Company Successfully Registered',
        ],Response::HTTP_CREATED);
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
        return response()->json([
            'message' => 'User Deatils',
            'data' => Auth()->user(),
            'emp_details'=> $emp_details
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