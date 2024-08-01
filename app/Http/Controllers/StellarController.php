<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AttendanceSetting;
use App\Models\Attendance; // Add this line to import the Attendance class
use App\Models\officeLocation;
use App\Models\RemoteEmployee;
use App\Models\AttendanceType;
use App\Models\AttendanceLocation;
use App\Models\Shift;
use App\Models\ShiftEmployee;
use App\Models\ShiftWeekend;
use App\Models\Weekend;
use App\Models\Company;
use App\Models\Employee;
use App\Models\StellarSetting;
use Illuminate\Support\Facades\Log;

class StellarController extends Controller
{
    public function attendanceCheck()
    {
        $start_date = Carbon::now()->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');
        $companies = Company::all();
        $allEmpIds = [];
        foreach($companies as $company){
            $company_id = $company->company_id;
            $takePresent = $this->determineAttendanceType($company_id);
            // dd($takePresent);
            if ($takePresent == 0) {
                continue; // Skip the rest of this iteration and move to the next company
            }
            $emp_list = Employee::where('company_id', $company_id)->pluck('emp_id');
            foreach($emp_list as $emp_id){
                $shiftEmployee = $this->getShiftEmployee($company_id, $emp_id);
                $isWeekend = $this->checkWeekend($company_id, $shiftEmployee);
                if ($isWeekend) {
                    Log::channel('attendance')->info('Attendance cannot be submitted on weekends for company_id: ' . $company_id);
                    continue; // Skip the rest of this iteration and move to the next employee  
                }
                $hasCheckedIn = $this->hasCheckedInToday($emp_id);
                if ($hasCheckedIn) {
                    continue; // Skip the rest of this iteration and move to the next employee
                }
                $allEmpIds[] = $emp_id;  
            }
            // dd($allEmpIds);
            $officeTime = $this->officeTime($shiftEmployee, $company_id);
            // dd($officeTime);
            $startTime = $officeTime['start_time'];
            $endTime = $officeTime['end_time'];
            $totalTime = $officeTime['totalTime'];
            // dd($startTime);
            $auth_user = StellarSetting::where('company_id', $company_id)->value('auth_user');
            $auth_code = StellarSetting::where('company_id', $company_id)->value('auth_code');
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://rumytechnologies.com/rams/json_api',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "operation" => "fetch_log",
                "auth_user" => $auth_user,
                "auth_code" => $auth_code,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "start_time" => $startTime,
                "end_time" => $endTime,
                "access_id" => 63271562
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: session_id_rams=103.198.139.120-9be74a9e-2fbc-4f7f-afc6-4f4b4d2c697f'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // dd($response);
            // Decode JSON
            $data = json_decode($response, true)['log'];
            if(!$data){
                Log::channel('attendance')->info('No attendance data found for company_id: ' . $company_id);
                continue; // Skip the rest of this iteration and move to the next company
            }
            // Initialize an empty array to hold the first access time for each user
            $firstAccessTimes = [];

            // Iterate through each log entry
            foreach ($data as $entry) {
                $user = $entry['registration_id'];
                $accessTime = $entry['access_time'];
                
                // If the user is not already in the array, or if the current access time is earlier than the stored one
                if (!isset($firstAccessTimes[$user]) || $accessTime <  $firstAccessTimes[$user]['access_time']) {
                    $firstAccessTimes[$user] = $entry;
                }
            }
            // dd($firstAccessTimes);
            $attendanceTakenEmpID = [];
            foreach($allEmpIds as $emp_id){
                foreach($firstAccessTimes as $data){
                    $access_time = $data['access_time'];
                    if ($emp_id == $data['registration_id']) {
                        $INstatus = ($access_time <= $totalTime) ? 1 : 2;
                        
                        if (!in_array($emp_id, $attendanceTakenEmpID)) {
                            $attendanceTakenEmpID[] = $emp_id;
                            $user_id = Employee::where('emp_id', $emp_id)->value('id');
                            $attendance = new Attendance();
                            $attendance->IN = 1;
                            $attendance->INstatus = $INstatus;
                            $attendance->emp_id = $emp_id;
                            $attendance->company_id = $company_id;
                            $attendance->id = $user_id;
                            $attendance->save();
                        }
                    }
                }
            }
            // dd($attendanceTakenEmpID);
            Log::channel('attendance')->error('Attendance taken for company_id: ' . $company_id . ' for emp_ids: ' . implode(',', $attendanceTakenEmpID));

        }
        return response()->json(['message' => 'Attendance checked successfully']);
    }

    private function determineAttendanceType($company_id)
    {
        $attendanceType = AttendanceType::where('company_id', $company_id)->first();
        if (!$attendanceType) {
            Log::channel('attendance')->error('Attendance Type not set for company_id: ' . $company_id);
            return 0;
        }
        $attendanceTypes = array_keys(array_filter($attendanceType->getAttributes(), function ($value) {
            return $value === 1;
        }));

        if (empty($attendanceTypes)) {
            Log::channel('attendance')->error('Attendance Type not set for company_id: ' . $company_id);
            return 0;
        }

        if (in_array("iot_based", $attendanceTypes)) {
            return 1;
        }

        return 0;
    }

    private function getShiftEmployee($company_id, $emp_id)
    {
        return ShiftEmployee::where('company_id', $company_id)
            ->get()
            ->filter(function ($shift) use ($emp_id) {
                $shiftEmpList = json_decode($shift->shift_emp_list, true);
                return collect($shiftEmpList)->contains('emp_id', $emp_id);
            })
            ->first();
    }

    private function checkWeekend($company_id, $shiftEmployee)
    {
        if ($shiftEmployee) {
            $weekendData = ShiftWeekend::where('company_id', $company_id)->first();
        } else {
            $weekendData = Weekend::where('company_id', $company_id)->first();
        }

        $weekend = array_keys(array_filter($weekendData->getAttributes(), function ($value) {
            return $value === 1;
        }));

        $currentDayName = Carbon::now()->format('l');
        return in_array($currentDayName, $weekend);
    }

    private function hasCheckedInToday($emp_id)
    {
        return Attendance::where('emp_id', $emp_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
    }

    private function officeTime($shiftEmployee, $company_id)
    {
        if ($shiftEmployee) {
            $shift_time = Shift::find($shiftEmployee->shifts_id);
            $start_time = Carbon::createFromFormat('H:i:s',$shift_time->shifts_start_time);
            $startTime = $start_time->format('H:i:s');
            $end_time = Carbon::createFromFormat('H:i:s',$shift_time->shifts_end_time);
            $shifts_grace_time = Carbon::createFromFormat('H:i:s',$shift_time->shifts_grace_time);
            $totalTime = $start_time->addHours($shifts_grace_time->hour)
                            ->addMinutes($shifts_grace_time->minute)
                            ->addSeconds($shifts_grace_time->second);
            $totalTime = $totalTime->format('H:i:s');
            $end_time = $end_time->format('H:i:s');
        } else {
            $start_time = Carbon::createFromFormat('H:i:s', AttendanceSetting::where('company_id', $company_id)->value('start_time'));
            $startTime = $start_time->format('H:i:s');
            $end_time = Carbon::createFromFormat('H:i:s', AttendanceSetting::where('company_id', $company_id)->value('end_time'));
            $graceTime = Carbon::createFromFormat('H:i:s', AttendanceSetting::where('company_id', $company_id)->value('grace_time'));
            $totalTime = $start_time->addHours($graceTime->hour)
                        ->addMinutes($graceTime->minute)
                        ->addSeconds($graceTime->second);
            $totalTime = $totalTime->format('H:i:s');
            $end_time = $end_time->format('H:i:s');
        }

        return [
            'start_time' => $startTime,
            'end_time' => $end_time,
            'totalTime' => $totalTime
        ];
    }
}
