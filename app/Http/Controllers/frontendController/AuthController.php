<?php

namespace App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class AuthController extends Controller
{
    protected $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = BaseUrl::get();
    }

    public function registrationForm(){
        $baseUrl = $this->baseUrl;
        return view('frontend.registration', ['baseUrl' => $baseUrl]);
    }

    public function loginForm(){
        if(session()->has('access_token') && session('access_token') !== null){
            return redirect()->route('dashboard'); 
        }else{
            return view('frontend.login');
        }
    }

    public function logout(Request $request){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/logout',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $access_token),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response,true);
        // dd($response);
        if($response == null){
            $request->session()->flush();
            return redirect()->route('loginForm');
        }
        if($response['status'] == 200){
            $request->session()->flush();
            return redirect()->route('loginForm');
        }else{
            return redirect()->route('loginForm');
        }
        
    }

    public function login(Request $request){
        if(!session('access_token')){

            $email = $request->email;
            $password = $request->password;
            $baseUrl = $this->baseUrl;

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $baseUrl.'/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "email":"'.$email.'",
                "password":"'.$password.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));
    
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response,true);
            if($data['status'] == 401){
                return redirect('/login-form')->with('error','Invalid credentials. Please try again.'); 
            }
            // dd($data);
            $access_token = $data['access_token'];
            session([
                'access_token' => $access_token,
                'role' => $data['user']['role'],
                'company_id' => $data['user']['company_id'],
                'name' => $data['user']['name'],
                'email' =>$data['user']['email'],
            ]);
        }else{
            $access_token = session('access_token');
        }
        
        return redirect()->route('dashboard'); 
    }
    

    public function registration(Request $request){
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
        $data->company_status = 'pending';
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
        
        return response()->json('Successfully Registered');
    }

    public function test(){
        return view('frontend.test');
    }

    public function showPasswordChange(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        return view('frontend.passwordChange', ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

}
