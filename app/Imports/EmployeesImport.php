<?php
// app/Imports/EmployeesImport.php

namespace App\Imports;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Validation\ValidationException;



class EmployeesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
       
        $validationErrors = [];
        foreach ($rows as $row) {

            $validator = Validator::make($row->all(), [
                'officeEmployeeID' => 'string|unique:employees,officeEmployeeID',
                'name' => 'required|string|between:2,100',
                'gender' => 'string',
                'dob' => 'required',
                'salary' => 'required|integer',
                'joining_date' => 'required',
                'dept_id' => 'required|integer',
                'designation_id' => 'required|integer',
                'phone_number' => 'digits:10',
                'email' => 'required|string|email|max:100|unique:users'
            ]);
        
            if ($validator->fails()) {
                $validationErrors[] = [
                    'row' => $row->toArray(),
                    'errors' => $validator->errors(),
                ];
            } 
        }
        
        if (!empty($validationErrors)) {
            throw ValidationException::withMessages([
                'import' => ['Validation error during import'],
                'errors' => $validationErrors,
            ]);
        }else{
            
            foreach ($rows as $row) {
                $company_id = auth()->user()->company_id;
                $user = new User();
                $user->name = $row['name'];
                $user->email = $row['email'];
                $user->password = bcrypt('123456789');
                $user->company_id = $company_id;
                $user->save();
    
                $user_id = User::where('email',$row['email'])->value('id');
                $joiningDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['joining_date']);
                $joiningDateOnly = $joiningDate->format('Y-m-d');
                $dob = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['joining_date']);
                $dobOnly = $dob->format('Y-m-d');
                $data=new Employee();
                $data->id = $user_id;
                $data->officeEmployeeID = $row['officeemployeeid'];
                $data->name = $row['name'];
                $data->gender = $row['gender'];
                $data->dob = $dobOnly;
                $data->joining_date = $joiningDateOnly;
                $data->phone_number = '0'.$row['phone_number'];
                $data->dept_id = $row['dept_id'];
                $data->designation_id = $row['designation_id'];
                $data->status = 1;
                $data->company_id = $company_id;
                $data->save();

                $sal = new Salary();
                $sal->salary = $row['salary'];
                $sal->joining_date =  $joiningDateOnly; 
                $sal->last_increment_date =  $joiningDateOnly; 
                $sal->emp_id = $data->emp_id; 
                $sal->company_id = $company_id; 
                $sal->save();
            }
        }
        
    }
}
