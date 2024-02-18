<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class payslipController extends Controller
{
    public function payslip(){
        $access_token = session('access_token');
        return view('frontend.payslipGenerate', ['jwtToken' => $access_token]);
    }
}
