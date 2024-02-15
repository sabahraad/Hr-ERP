<?php

namespace App\Http\Controllers\frontendController;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DesigExport;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class designationController extends Controller
{
    public function designation(){
        
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
        return view('frontend.designation',compact('dataArray'), ['jwtToken' => $access_token]);
   
    }

    public function exportDesigData()
    {
        $company_id = session('company_id');
        $data = Designation::join('departments', 'designations.dept_id', '=', 'departments.dept_id')
                            ->where('departments.company_id', $company_id)
                            ->select('designations.designation_id', 'designations.desigTitle', 'designations.dept_id', 'departments.deptTitle')
                            ->get();
        return Excel::download(new DesigExport($data), 'designation_data.xlsx');
    }
}
