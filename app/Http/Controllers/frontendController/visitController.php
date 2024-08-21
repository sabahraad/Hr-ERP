<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class visitController extends Controller
{
    public function showVisitReport(){
        $access_token = session('access_token');
        $baseUrl = BaseUrl::get();
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
        $emp = json_decode($response,true);
        return view('frontend.visitReport',compact('emp'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }
}
