<?php

namespace App\Http\Controllers\frontendController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftEmployee;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

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

    public function showAddEmployeeInShift(){
        $access_token = session('access_token');
        $compnany_id = session('company_id');
        $shift = Shift::where('company_id',$compnany_id)->get();

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hrm.aamarpay.dev/api/all-employee-list',
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

        $value = ShiftEmployee::where('shift_employees.company_id', $compnany_id)
                                ->join('shifts', 'shifts.shifts_id', '=', 'shift_employees.shifts_id')
                                ->select('shift_employees.*', 'shifts.shifts_title', 'shifts.shifts_start_time', 'shifts.shifts_end_time')
                                ->get();
        return view('frontend.addEmployeeInShift',compact('shift','employee','value'));
    }

    public function addEmployeeInShift(Request $request){
        $compnany_id = session('company_id');
        $existingData = ShiftEmployee::where('shifts_id', $request->shifts_id)
                                    ->where('company_id', $compnany_id)
                                    ->first();
        if ($existingData) {
            $existingList = json_decode($existingData->shift_emp_list, true);

            foreach ($request->emp_id as $data) {
                // Check if emp_id already exists in the list
                if (!in_array($data, array_column($existingList, 'emp_id'))) {
                    $value = Employee::where('emp_id', $data)->value('name');
                    $existingList[] = [
                        'emp_id' => $data,
                        'emp_name' => $value,
                    ];
                }
            }

            $existingData->shift_emp_list = json_encode($existingList);
            $existingData->save();
        } else {
            $result = [];

            foreach ($request->emp_id as $data) {
                // Check if emp_id already exists in the result list
                if (!in_array($data, array_column($result, 'emp_id'))) {
                    $value = Employee::where('emp_id', $data)->value('name');
                    $result[] = [
                        'emp_id' => $data,
                        'emp_name' => $value,
                    ];
                }
            }

            $newData = new ShiftEmployee();
            $newData->shift_emp_list = json_encode($result);
            $newData->shifts_id = $request->shifts_id;
            $newData->company_id = $compnany_id;
            $newData->save();
        }

        return redirect()->route('showAddEmployeeInShift')->with('success', 'Employee Added successfully.');
    }

    public function showRemoveEmployeeFromShift($id){
        $value = ShiftEmployee::where('shift_employees.shift_employees_id', $id)
                                ->join('shifts', 'shifts.shifts_id', '=', 'shift_employees.shifts_id')
                                ->select('shift_employees.*', 'shifts.shifts_title', 'shifts.shifts_start_time', 'shifts.shifts_end_time')
                                ->get();
        return view('frontend.removeEmployeeFromShift',compact('value'));
    }

    public function removeEmployeeFromShift(Request $request){
        $data = ShiftEmployee::find($request->shift_employees_id);
        $employees = json_decode($data->shift_emp_list, true);

        // Array of employee IDs to remove
        $employeesToRemove = $request->emp_id;

        // Remove the specified employees from the array
        foreach ($employeesToRemove as $employeeId) {
            $indexToRemove = array_search($employeeId, array_column($employees, 'emp_id'));

            if ($indexToRemove !== false) {
                array_splice($employees, $indexToRemove, 1);
            }
        }
        // Encode the modified array back to JSON
        $modifiedJsonString = json_encode($employees);
        //edit the shift_emp_list
        $data->shift_emp_list = $modifiedJsonString;
        $data->save();
        return back()->with('success', 'Employee removed successfully.');
    }

}
