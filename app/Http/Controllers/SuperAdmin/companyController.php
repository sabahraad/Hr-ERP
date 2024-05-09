<?php

namespace App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Holiday;
use App\Utils\BaseUrl;
use Illuminate\Http\Request;

class companyController extends Controller
{
    public function companyList(){

        $data = Company::whereNull('deleted_at')
                    ->orderBy('company_id','desc')
                    ->get();
        return view('SuperAdmin.companyList',compact('data')); 
    }

    public function holidayList(){
        $access_token = session('access_token');
        $baseUrl = BaseUrl::get();
        $data = Holiday::whereNull('company_id')
        ->orderBy('holidays_id','desc')
        ->get();
        return view('SuperAdmin.hoilday',compact('data'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }
}
