<?php

namespace App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class companyController extends Controller
{
    public function companyList(){
        $data = DB::table('companies')->whereNull('deleted_at')
                    ->orderBy('company_id','desc')
                    ->get();
        return view('SuperAdmin.companyList',compact('data')); 
    }

    public function holidayList(){
        $data = DB::table('holidays')->whereNull('company_id')
        ->orderBy('holidays_id','desc')
        ->get();
        return view('SuperAdmin.hoilday',compact('data'));
    }
}
