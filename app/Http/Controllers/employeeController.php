<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class employeeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addEmployee(Request $request){

        $validator = Validator::make($request->all(), [
            'officeEmployeeID' => 'string',
            'name' => 'required|string|between:2,100',
            'gender' => 'string',
            'dob' => 'string',
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
        $data->phone_number = $request->phone_number;
        $data->dept_id = $request->dept_id;
        $data->designation_id = $request->designation_id;
        $data->status = $request->status;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message' => 'Employee Added Successful',
            'data' => $data
        ],201);
    }

    public function employeeList(){

        $company_id= auth()->user()->company_id;
        $data = Employee::where('company_id',$company_id)->get();
        return response()->json([
            'message'=> 'Employee List',
            'data'=>$data
        ],200);

    }

    public function updateEmployee(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'officeEmployeeID' => 'string',
            'name' => 'required|string|between:2,100',
            'gender' => 'string',
            'dob' => 'string',
            'dept_id' => 'required|integer',
            'phone_number' => 'digits:11',
            'designation_id' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'string',
            'email' => 'required|string|email|max:100',
            'password' => 'string|confirmed|min:6' 
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $userInfo = User::where('email',$request->email)->first();
        $company_id= auth()->user()->company_id;
        if(!$userInfo){
            return response()->json([
                'message' => 'User Not Found',
            ],404);
        }

        if($request->has('password')){
            // $userInfo->update(['password' => $request->password]);
            $userInfo->name = $request->name;
            $userInfo->email = $request->email;
            $userInfo->password = bcrypt($request->password);
            $userInfo->company_id = $company_id;
            $userInfo->save();
        }

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

        $user_id = Employee::where('emp_id',$id)->value('id');
        $data->id = $user_id;
        $data->officeEmployeeID = $request->officeEmployeeID ?? $data->officeEmployeeID;
        $data->name = $request->name;
        $data->gender = $request->gender ?? $data->gender;
        $data->dob = $request->dob ?? $data->dob;
        $data->phone_number = $request->phone_number ?? $data->phone_number;
        $data->dept_id = $request->dept_id;
        $data->designation_id = $request->designation_id;
        $data->status = $request->status ?? $data->status;
        $data->company_id = $company_id;
        $data->save();

        return response()->json([
            'message' => 'Employee Updated Successful',
            'data' => $data
        ],200);
    }

    public function deleteEmployee($id){
        Employee::where('emp_id',$id)->delete();        
        return response()->json([
            'message' => 'Employee deleted successfully'
        ]);
    }
}