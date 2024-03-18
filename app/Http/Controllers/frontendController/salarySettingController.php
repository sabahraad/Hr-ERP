<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use App\Models\tempSalarySetting;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class salarySettingController extends Controller
{
    public function salarySetting(){
        $access_token = session('access_token');
        $company_id = session('company_id');
        $baseUrl = BaseUrl::get();
        $data = tempSalarySetting::where('company_id',$company_id)->get();
        return view('frontend.salarySetting',compact('data'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }
}
