<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class payslipController extends Controller
{
    public function payslip(){
        $access_token = session('access_token');
        $baseUrl = BaseUrl::get();
        return view('frontend.payslipGenerate', ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }
}
