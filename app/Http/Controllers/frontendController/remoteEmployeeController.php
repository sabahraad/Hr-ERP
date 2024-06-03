<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\RemoteEmployee;
use Illuminate\Http\Request;

class remoteEmployeeController extends Controller
{
    public function remoteEmployeeList(){
        $company_id = Session('company_id');
        $result = RemoteEmployee::where('company_id', $company_id)
                                    ->get();
        $employee = Employee::where('company_id',$company_id)->get();
        // Extract emp_id values from the $result collection
        $existingEmpIds = $result->flatMap(function ($item) {
            return collect(json_decode($item->employee_ids))->pluck('emp_id')->toArray();
        });

        // Filter the $emp collection to exclude existing emp_ids
        $emp = $employee->whereNotIn('emp_id', $existingEmpIds);
        return view('frontend.remoteEmployee',compact('result','emp'));
    }

    public function addEmployeeIntoRemote(Request $request){
        $company_id = Session('company_id');
        $existingRecord = RemoteEmployee::where('company_id', $company_id)->first();
        // If record exists, update the employee_ids attribute by appending the new selectedEmployees
        if ($existingRecord) {
            $selectedEmployees = json_decode($existingRecord->employee_ids, true);
            $newSelectedEmployees = json_decode($request->selectedEmployees, true);

            // Merge the new selectedEmployees with the existing ones
            $mergedEmployees = array_merge($selectedEmployees, $newSelectedEmployees);

            // Remove duplicate employees by creating a unique array based on emp_id
            $uniqueEmployees = collect($mergedEmployees)->unique('emp_id')->values()->all();

            // Update the existing record with the merged employee_ids
            $existingRecord->employee_ids = json_encode($uniqueEmployees);
            $existingRecord->save();
            return redirect()->route('remoteEmployeeList')->with('success','Employee Added');
        }else{
            $data = new RemoteEmployee();
            $data->company_id = $company_id;
            $data->employee_ids = $request->selectedEmployees;
            $data->save();
            return redirect()->route('remoteEmployeeList')->with('success','Employee Added');
        }
        
    }

    public function editEmployeeIntoRemote($id){
        $company_id = Session('company_id');
        $data = RemoteEmployee::where('company_id',$company_id)->where('remote_employees_id',$id)->first();
        $emp = Employee::where('company_id',$company_id)->get();
        return view('frontend.editRemoteEmployee',compact('emp','data'));
    }

    public function updateEmployeeIntoRemote(Request $request){
        $company_id = Session('company_id');
        $data = RemoteEmployee::where('company_id',$company_id)->where('remote_employees_id',$request->remote_employees_id)->first();
        $data->employee_ids = $request->selectedEmployees;
        $data->save();
        return redirect()->route('remoteEmployeeList')->with('success','Employee Added');
    }

    public function deleteEmployeeIntoRemote(Request $request){
        RemoteEmployee::destroy($request->remote_employees_id);
        return back()->with('success','Deleted Successfully');
    }
}
