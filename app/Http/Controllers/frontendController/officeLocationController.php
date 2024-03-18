<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class officeLocationController extends Controller
{
    public function officeLocation(){

        $access_token = session('access_token');
        $baseUrl = BaseUrl::get();
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/office-location-list',
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
        return view('frontend.officeLocation',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }
}
