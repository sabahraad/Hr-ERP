<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
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
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'company_id' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        
        return response()->json([
            'message' => 'User successfully registered',
            'data' => $user
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