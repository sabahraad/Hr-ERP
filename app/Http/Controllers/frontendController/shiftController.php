<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class shiftController extends Controller
{
    public function ShiftList(){
        $access_token = session('access_token');
        $compnany_id = session('company_id');
        $data = Shift::where('company_id',$compnany_id)->orderBy('updated_at', 'desc')->get();
        return view('frontend.shiftList',compact('data'), ['jwtToken' => $access_token]);
    }

    public function addShift(){
        return view('frontend.addShift');
    }

    public function createShift(Request $request){
        $compnany_id = session('company_id');
        $data = new Shift();
        $data->shifts_title = $request->shifts_title;
        $data->shifts_start_time = $request->shifts_start_time;
        $data->shifts_end_time = $request->shifts_end_time;
        $data->shifts_grace_time = $request->shifts_grace_time;
        $data->company_id = $compnany_id;
        $data->save();
        return redirect()->route('ShiftList');
    }

    public function showEditShift($id){
        $data = Shift::where('shifts_id',$id)->get();
        return view('frontend.editShift',compact('data'));
    }

    public function editShift(Request $request){
        $data = Shift::find($request->shifts_id);
        $data->shifts_title = $request->shifts_title;
        $data->shifts_start_time = $request->shifts_start_time;
        $data->shifts_end_time = $request->shifts_end_time;
        $data->shifts_grace_time = $request->shifts_grace_time;
        $data->save();
        return redirect()->route('ShiftList');
    }

    public function deleteShift($id){
        Shift::destroy($id);
        return redirect()->route('ShiftList');
    }
}
