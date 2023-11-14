<?php

namespace App\Http\Controllers;

use App\Models\officeNotice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class officeNoticeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addNotice(Request $request){

        $validator = Validator::make($request->all(), [
                'notice' => 'required|string',
                'attachment' => 'mimes:jpeg,png,gif,svg,pdf,xlsx,xls',
            ]);

            $company_id = auth()->user()->company_id;

            if($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                ], 422);
            }
            $data = new officeNotice();
            $data->notice = $request->notice;
            $data->company_id = $company_id;

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

            $data->save();

            return response()->json([
                'message'=>'Office Notice Added Successfully',
                'data'=>$data
            ],201);
    }

    public function noticeList(){
        $company_id = auth()->user()->company_id;
        $data = officeNotice::where('company_id',$company_id)->get();
        return response()->json([
            'message'=>'Office Notice',
            'data'=>$data
        ],200);
    }

    public function editNotice(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'notice' => 'required|string',
            'attachment' => 'mimes:jpg,jpeg,png,gif,svg,pdf,xlsx,xls',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $data = officeNotice::find($id);
        $data->notice = $request->notice;
        if($request->has('attachment')){
            
            $extension = $request->attachment->getClientOriginalExtension();
            if ($extension === 'pdf') {
                $pdfPath = $request->attachment->storeAs('pdfs', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$pdfPath;
            }
        
            if (in_array($extension, ['jpg','jpeg', 'png', 'gif', 'svg'])) {
                    $imagePath = $request->attachment->storeAs('images', time() . '.' . $extension, 'public');
                    $data->attachment = 'storage/'.$imagePath;
            }
        
            if (in_array($extension, ['xlsx', 'xls'])) {
                $excelPath = $request->attachment->storeAs('excels', time() . '.' . $extension, 'public');
                $data->attachment = 'storage/'.$excelPath;
            }
        }
        
        $data->save();

        return response()->json([
            'message'=>'Office Notice Edited Successfully',
            'data'=>$data
        ],200);
    }

    public function deleteNotice($id){
        officeNotice::destroy($id);
        return response()->json([

        ],204);
    }
}
