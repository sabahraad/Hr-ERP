<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class incrementController extends Controller
{
    protected $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = BaseUrl::get();
    }

    public function increment(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/employee-salary-details',
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
        return view('frontend.increment',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

    public function incrementHistory(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/emp-list',
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
        return view('frontend.incrementHistory',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }
}
