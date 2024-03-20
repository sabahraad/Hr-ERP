<?php

namespace App\Http\Controllers\frontendController;
use App\Models\officeNotice;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\BaseUrl;

class officeNoticeController extends Controller
{
    public function officeNotice(){
        $access_token = session('access_token');
        $baseUrl = BaseUrl::get();
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/notice-list',
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
        return view('frontend.officeNotice',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]); 
    }

    public function addOfficeNotice(){
       return view('frontend.addOfficeNotice');
    }

    public function createOfficeNotice(Request $request){
        $company_id = session('company_id');
        $validator = Validator::make($request->all(), [
            'notice' => 'required|string',
            'attachment' => 'mimes:jpeg,png,gif,jpg,svg,pdf,xlsx,xls',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = new officeNotice();
        $data->notice = $request->notice;
        $data->company_id = $company_id;
        if($request->has('attachment')){

            $extension = $request->attachment->getClientOriginalExtension();

            if ($extension === 'pdf') {
                $pdfPath = $request->attachment->storeAs('pdfs', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$pdfPath;
            }
        
            if (in_array($extension, ['jpeg', 'png', 'gif','jpg', 'svg'])) {
                    $imagePath = $request->attachment->storeAs('images', time() . '.' . $extension, 'public');
                    $data->attachment = 'storage/'.$imagePath;
            }
        
            if (in_array($extension, ['xlsx', 'xls'])) {
                $excelPath = $request->attachment->storeAs('excels', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$excelPath;
            }
        }

        $data->save();

        return redirect()->route('officeNotice')->with('success', 'Office Notice Added Successfully');
        
    }

    public function showEditOfficeNotice($id){
        $data = officeNotice::find($id);
        return view('frontend.editOfficeNotice',compact('data'));
    }

    public function editOfficeNotice(Request $request,$id){
        $company_id = session('company_id');
        $validator = Validator::make($request->all(), [
            'notice' => 'required|string',
            'attachment' => 'mimes:jpeg,png,gif,svg,jpg,pdf,xlsx,xls',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = officeNotice::find($id);
        $data->notice = $request->notice;
        $data->company_id = $company_id;
        if($request->has('attachment')){

            $extension = $request->attachment->getClientOriginalExtension();

            if ($extension === 'pdf') {
                $pdfPath = $request->attachment->storeAs('pdfs', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$pdfPath;
            }
        
            if (in_array($extension, ['jpeg', 'png', 'gif', 'svg'])) {
                    $imagePath = $request->attachment->storeAs('images', time() . '.' . $extension, 'public');
                    $data->attachment = 'storage/'.$imagePath;
            }
        
            if (in_array($extension, ['xlsx', 'xls'])) {
                $excelPath = $request->attachment->storeAs('excels', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$excelPath;
            }
        }

        $data->save();

        return redirect()->route('officeNotice')->with('success', 'Office Notice Edited Successfully');

    }
}
