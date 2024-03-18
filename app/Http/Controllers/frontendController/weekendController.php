<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class weekendController extends Controller
{
    public function weekendlist(){

        $access_token = session('access_token');
        $baseUrl = BaseUrl::get();
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hrm.aamarpay.dev/api/weekend-list',
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
        return view('frontend.weekend',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }
    public function save(Request $request){
        dd($request);
    }
}
