<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\Weekend;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class holidayController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function createHoliday(Request $request)
    {
        $validator = $this->validateHolidayRequest($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $date = $request->date_range;
        $dateParts = explode(' - ', $date);
        $startDate = $dateParts[0];
        $endDate = $dateParts[1];

        $dateList = $this->calculateDateRange($startDate,$endDate);
        $filteredDates = $this->filterWeekendDates($dateList);

        $jsonData = json_encode(array_values($filteredDates));
        $holiday = $this->storeHoliday($jsonData, $request->reason);

        return response()->json(['message' => 'Holiday Created Successfully', 'data' => $holiday], 201);
    }

    public function updateHoliday(Request $request, $id)
    {
        $validator = $this->validateHolidayRequest($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $date = $request->date_range;
        $dateParts = explode(' - ', $date);
        $startDate = $dateParts[0];
        $endDate = $dateParts[1];

        $dateList = $this->calculateDateRange($startDate, $endDate);
        $filteredDates = $this->filterWeekendDates($dateList);

        $jsonData = json_encode(array_values($filteredDates));
        $holiday = $this->updateHolidayData($id, $jsonData, $request->reason);

        return response()->json(['message' => 'Holiday Updated Successfully', 'data' => $holiday], 200);
    }

    private function validateHolidayRequest($request)
    {
        return Validator::make($request->all(), [
            'date_range' => 'required',
            'reason' => 'required|string'
        ]);
    }

    private function calculateDateRange($start, $end)
    {
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        $dateList = [];

        while ($startDate <= $endDate) {
            $dateList[] = $startDate->toDateString();
            $startDate->addDay();
        }

        return $dateList;
    }

    private function filterWeekendDates($dateList)
    {
        $company_id = auth()->user()->company_id;
        $weekend = Weekend::where('company_id', $company_id)->first();
        $weekendDays = collect($weekend->getAttributes())->filter(function ($value, $key) {
            return $value === 1 && $key !== 'company_id';
        })->keys()->toArray();

        return array_filter($dateList, function ($date) use ($weekendDays) {
            return !in_array(Carbon::parse($date)->format('l'), $weekendDays);
        });
    }

    private function storeHoliday($date, $reason)
    {
        $holiday = new Holiday();
        $holiday->date = $date;
        $holiday->reason = $reason;
        $holiday->company_id = auth()->user()->company_id;
        $holiday->save();

        return $holiday;
    }

    private function updateHolidayData($id, $date, $reason)
    {
        $holiday = Holiday::find($id);
        $holiday->date = $date;
        $holiday->reason = $reason;
        $holiday->save();

        return $holiday;
    }

    public function HolidayList(){

        $company_id= auth()->user()->company_id;
        $data=Holiday::where(function ($query) use ($company_id) {
                        $query->where('company_id', $company_id)
                            ->orWhereNull('company_id');
                    })
                    ->get();
        
        return response()->json([
            'message'=>'Holiday List',
            'data'=>$data
        ],200);

    }

    public function deleteHoliday($id){
        Holiday::where('holidays_id',$id)->delete();        
        return response()->json([
            'message' => 'Holiday deleted successfully'
        ]);
    }

}
