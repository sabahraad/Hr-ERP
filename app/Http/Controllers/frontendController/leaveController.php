<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class leaveController extends Controller
{
    protected $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = BaseUrl::get();
    }

    public function leaveList(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/leave-type-list',
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
        return view('frontend.leaveType',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

    public function leaveApprover(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/department-list',
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

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/all-employee-list',
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
        $employee = json_decode($response,true);
        return view('frontend.leaveApprover',compact('dataArray','employee'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
        
    }

    public function addLeaveApprover(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/department-list',
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

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/all-employee-list',
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
        $employee = json_decode($response,true);
        return view('frontend.addLeaveApprover',compact('dataArray','employee'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

    public function allLeaveApplication(){

        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/all-leave-application-only-for-hr',
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
        $leaveApplicationList = json_decode($response,true);
        return view('frontend.allLeaveApplication',compact('leaveApplicationList'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

    public function leaveReport(){
        $access_token = session('access_token');
        $baseUrl = $this->baseUrl;
        return view('frontend.leaveReport', ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

}
