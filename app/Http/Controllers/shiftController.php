<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class shiftController extends Controller
{
    public function ShiftList(){
        $access_token = session('access_token');
        return view('frontend.shiftList', ['jwtToken' => $access_token]);
    }
}
