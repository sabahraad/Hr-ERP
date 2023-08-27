<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $this->createNewToken($token);
    }
   
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string|unique:companies',
            'address' => 'required|string',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'companyDetails' => 'required',
            'contactNumber' => 'required',
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

        $company_id = Company::where('companyName',$request->companyName)->value('company_id');

        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->company_id = $company_id;
        $user->role = 2;
        $user->save();

        // $user = User::create(array_merge(
        //             $validator->validated(),
        //             ['password' => bcrypt($request->password)]
        //         ));
        
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
        
        return response()->json([
            'message' => 'User Deatils',
            'data' => Auth()->user()
        ],Response::HTTP_OK);
            
    }
  
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => JWTAuth::manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['exp'],

            'expires_in' => Auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}