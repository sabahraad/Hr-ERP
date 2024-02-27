<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class incrementController extends Controller
{
    public function increment(){
        $access_token = session('access_token');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hrm.aamarpay.dev/api/employee-salary-details',
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
        return view('frontend.increment',compact('dataArray'), ['jwtToken' => $access_token]);
    }

    public function incrementHistory(){
        $access_token = session('access_token');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hrm.aamarpay.dev/api/emp-list',
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
        return view('frontend.incrementHistory',compact('dataArray'), ['jwtToken' => $access_token]);
    }
}