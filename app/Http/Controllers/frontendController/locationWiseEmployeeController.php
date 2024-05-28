<?php

namespace App\Http\Controllers\frontendController;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LocationWiseEmployee;
use App\Models\officeLocation;
use Illuminate\Http\Request;

class locationWiseEmployeeController extends Controller
{
    public function addEmployeeIntoLocation(Request $request){
        $company_id = Session('company_id');
        // Check if a record with the provided office_locations_id already exists
        $existingRecord = LocationWiseEmployee::where('office_locations_id', $request->office_locations_id)
        ->where('company_id', $company_id)
        ->first();

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

            return redirect()->route('LoctionWiseEmployeeList')->with('success','Employees added successfully');
        }else{
            // If record doesn't exist, create a new one
            $data = new LocationWiseEmployee();
            $data->office_locations_id = $request->office_locations_id;
            $data->employee_ids = $request->selectedEmployees;
            $data->company_id = $company_id;
            $data->save();
            return redirect()->route('LoctionWiseEmployeeList')->with('success','Employees added successfully');
        }

    }

    public function LoctionWiseEmployeeList(){
        $company_id = Session('company_id');
        $data = officeLocation::where('company_id',$company_id)->get();
        $result = LocationWiseEmployee::with('officeLocation')
                                    ->where('company_id', $company_id)
                                    ->get();
        $employee = Employee::where('company_id',$company_id)->get();
        // Extract emp_id values from the $result collection
        $existingEmpIds = $result->flatMap(function ($item) {
            return collect(json_decode($item->employee_ids))->pluck('emp_id')->toArray();
        });

        // Filter the $emp collection to exclude existing emp_ids
        $emp = $employee->whereNotIn('emp_id', $existingEmpIds);
        return view('frontend.locationWiseEmployeeList',compact('data','result','emp'));
    }

    public function individualLoctionWiseEmployeeList(Request $request){
        $company_id = Session('company_id');
        $data = officeLocation::where('company_id',$company_id)->get();
        $result = LocationWiseEmployee::with('officeLocation')
                                    ->where('company_id', $company_id)
                                    ->get();
        $employee = Employee::where('company_id',$company_id)->get();
        // Extract emp_id values from the $result collection
        $existingEmpIds = $result->flatMap(function ($item) {
            return collect(json_decode($item->employee_ids))->pluck('emp_id')->toArray();
        });

        // Filter the $emp collection to exclude existing emp_ids
        $emp = $employee->whereNotIn('emp_id', $existingEmpIds);
        $result = LocationWiseEmployee::with('officeLocation')
                                    ->where('office_locations_id',$request->office_locations_id)
                                    ->get();
        return view('frontend.locationWiseEmployeeList',compact('data','result','emp'));
    }

    public function editEmployeeIntoLocation($id){
        $company_id = Session('company_id');
        $data = LocationWiseEmployee::with('officeLocation')
                                    ->find($id);
        $result = LocationWiseEmployee::with('officeLocation')
                                    ->where('company_id', $company_id)
                                    ->where('location_wise_employees_id', '!=', $id)
                                    ->get();
        $employee = Employee::where('company_id',$company_id)->get();
        // Extract emp_id values from the $result collection
        $existingEmpIds = $result->flatMap(function ($item) {
            return collect(json_decode($item->employee_ids))->pluck('emp_id')->toArray();
        });

        // Filter the $emp collection to exclude existing emp_ids
        $emp = $employee->whereNotIn('emp_id', $existingEmpIds);
        return view('frontend.editEmployeeIntoLocation',compact('data','emp'));
    }

    public function updateEmployeeIntoLocation(Request $request){
        $data = LocationWiseEmployee::find($request->location_wise_employees_id);
        $data->employee_ids = $request->selectedEmployees ?? $data->employee_ids;
        $data->save();
        return redirect()->route('LoctionWiseEmployeeList')->with('success','Employee List Updated');
    }
}
