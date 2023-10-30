<?php

namespace App\Http\Controllers\frontendController;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class departmentController extends Controller
{
    public function department(){
        $access_token = session('access_token');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hrm.aamarpay.dev/api/department-list',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $access_token),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $dataArray = json_decode($response,true);
        return view('frontend.department',compact('dataArray'), ['jwtToken' => $access_token]);   
    }

    
}
