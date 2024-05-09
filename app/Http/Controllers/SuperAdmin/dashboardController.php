<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function dashboard(){
        $data = null;
        return view('SuperAdmin.dashboard',compact('data'));
    }
}
