<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
        
        $customerName = 'John Doe';
        $customerEmail = $request->email;
        dispatch(new \App\Jobs\SendForgetPasswordEmailJob($customerEmail,$customerName)); 

        // dispatch(new SendCustomerEmailJob($customerName, $customerEmail));

        return response()->json([
            'message' => 'Email Sent'
        ],200);
    }

    public function resetPassword(){
        return view('resetPassword');
    }
}
