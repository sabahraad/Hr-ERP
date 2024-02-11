<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\OTP;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Jobs\SendForgetPasswordEmailJob;
use App\Models\User;


class forgetPasswordEmailController extends Controller
{
    public function emailData(Request $request){

        $validator= Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        
        $OTP = rand(11111,99999);
        $customerEmail = $request->email;
        $OTPData = OTP::where('email',$customerEmail)->value('id');
        if($OTPData == null){
            $data = new OTP();
            $data->email = $customerEmail;
            $data->OTP = $OTP;
            $data->status = 1;
            $data->save();
        }else{
            $data = OTP::find($OTPData);
            $data->OTP = $OTP;
            $data->status  = 2;
            $data->save();
        }

        dispatch(new SendForgetPasswordEmailJob($customerEmail,$OTP));
        return response()->json([
                    'message' => 'Email send successfully to ' .$customerEmail,
                ],200); 
    }

    public function verifyOTP(Request $request){
        $validator= Validator::make($request->all(), [
            'email' => 'required|email',
            'OTP' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        
        $data = OTP::where('email',$request->email)->first();
        if($data){
            $otpArray = $data->toArray();
            if($data['status'] == 1){
                $currentTime = Carbon::now();
                $createdAt = Carbon::parse($data['created_at']);
                $diffInMinutes = $currentTime->diffInMinutes($createdAt);
                if($data['OTP'] == $request->OTP){
                    if ($diffInMinutes <= 2) {
                        DB::table('o_t_p_s')->where('email', $request->email)->update(['status' => 3]);
                        return response()->json([
                            'message'=>'OTP Verified'
                        ],202);
                    }else{
                        return response()->json([
                            'message'=> 'Your OTP is expired, Please try with new OTP'
                        ],410);
                    }
                }else{
                    return response()->json([
                        'message'=>'Wrong OTP'
                    ],400);
                }
            }else{
                $currentTime = Carbon::now();
                $updatedAt = Carbon::parse($data['updated_at']);
                $diffInMinutes = $currentTime->diffInMinutes($updatedAt);
                if($data['OTP'] == $request->OTP){
                    if ($diffInMinutes <= 2) {
                        DB::table('o_t_p_s')->where('email', $request->email)->update(['status' => 3]);
                        return response()->json([
                            'message'=>'OTP Verified'
                        ],202);
                    }else{
                        return response()->json([
                            'message'=> 'Your OTP is expired, Please try with new OTP'
                        ],410);
                    } 
                }else{
                    return response()->json([
                        'message'=>'Wrong OTP'
                    ],400);
                }
            }
            
        }else{
            return response()->json([
                'message'=>'Please request for OTP first'
            ],401);
        }

    }

    public function forgetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:6'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $data = OTP::where('email',$request->email)->value('status');
        $verified = 3;
        if($data == $verified){
            $user = User::where('email',$request->email)->first();
            if($user){
                $user->password = bcrypt($request->password);
                $user->save();
                OTP::where('email',$request->email)->delete();
                return response()->json([
                    'message' => 'Your password has been changed'
                ],201);
            }else{
                return response()->json([
                    'message' => $request->email .' '.'is not registered in the system'
                ],404);
            }
        }else{
            return response()->json([
                'message' => 'Please Verify OTP First'
            ],401 );
        }
        
    }
  
    public function resetPassword(){
        return view('resetPassword');
    }
    
}
