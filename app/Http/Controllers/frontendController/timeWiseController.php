<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class timeWiseController extends Controller
{
    public function timeWise(){
        return view('timewise');
    }
}
