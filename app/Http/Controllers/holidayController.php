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

        $dateList = $this->calculateDateRange($request->start_date, $request->end_date);
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

        $dateList = $this->calculateDateRange($request->start_date, $request->end_date);
        $filteredDates = $this->filterWeekendDates($dateList);

        $jsonData = json_encode(array_values($filteredDates));
        $holiday = $this->updateHolidayData($id, $jsonData, $request->reason);

        return response()->json(['message' => 'Holiday Updated Successfully', 'data' => $holiday], 200);
    }

    private function validateHolidayRequest($request)
    {
        return Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
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

    // public function createHoliday(Request $request){

    //     $validator = Validator::make($request->all(), [
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date',
    //         'reason' => 'required|string'
    //     ]);
        
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => $validator->errors(),
    //         ], 422);
    //     }
    //     $company_id= auth()->user()->company_id;
    //     $startDate = Carbon::parse($request->start_date);
    //     $endDate = Carbon::parse($request->end_date);
    //     //Holiday Date List
    //     if(($startDate <= $endDate) == true){
    //         while ($startDate <= $endDate) {
    //             $dateList[] = $startDate->toDateString();
    //             $startDate->addDay();
    //         }
    //     }else{
    //         return response()->json([
    //             'message'=> 'Holiday End Date Can Not Be Smaller Then The Start Date'
    //         ],400);
    //     }
    //     //get date and Day as key value pair
    //     foreach ($dateList as $date) {
    //         $carbonDate = Carbon::parse($date);
    //         $dayNames[] = $carbonDate->format('l'); // 'l' format gives the full day name
    //     }
    //     $keyValueDateList = array_combine( $dateList,$dayNames);

    //     $weekend=Weekend::where('company_id',$company_id)->first();
    //     $data=$weekend->getAttributes();
    //     $weekendDayNames = array_keys(array_filter($data, function($value) {
    //         return $value === 1;
    //     }));
    //     $weekendDayNames = array_diff($weekendDayNames, ["company_id"]);
        
    //     foreach($weekendDayNames as $raw){
    //         $dateListWithoutWeekend = array_filter($keyValueDateList, function($value) use ($raw) {
    //             return $value !== $raw ;
    //         });
    //         $keyValueDateList = $dateListWithoutWeekend ;
    //     }
    //     $dateArray=array_keys($dateListWithoutWeekend );
    //     $jsonData = json_encode($dateArray);
    //     $data=new Holiday();
    //     $data->date = $jsonData;
    //     $data->reason = $request->reason;
    //     $data->company_id = $company_id;
    //     $data->save();

    //     return response()->json([
    //         'message'=> 'Holiday Created Successfully',
    //         'data'=>$data
    //     ],201);
    // }

    

    // public function updateHoliday(Request $request,$id){

    //     $validator = Validator::make($request->all(), [
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date',
    //         'reason' => 'required|string'
    //     ]);
        
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => $validator->errors(),
    //         ], 422);
    //     }
    //     $company_id= auth()->user()->company_id;
    //     $startDate = Carbon::parse($request->start_date);
    //     $endDate = Carbon::parse($request->end_date);
    //     //Holiday Date List
    //     if(($startDate <= $endDate) == true){
    //         while ($startDate <= $endDate) {
    //             $dateList[] = $startDate->toDateString();
    //             $startDate->addDay();
    //         }
    //     }else{
    //         return response()->json([
    //             'message'=> 'Holiday End Date Can Not Be Smaller Then The Start Date'
    //         ],400);
    //     }
    //     //get date and Day as key value pair
    //     foreach ($dateList as $date) {
    //         $carbonDate = Carbon::parse($date);
    //         $dayNames[] = $carbonDate->format('l'); // 'l' format gives the full day name
    //     }
    //     $keyValueDateList = array_combine( $dateList,$dayNames);

    //     $weekend=Weekend::where('company_id',$company_id)->first();
    //     $data=$weekend->getAttributes();
    //     $weekendDayNames = array_keys(array_filter($data, function($value) {
    //         return $value === 1;
    //     }));
    //     $weekendDayNames = array_diff($weekendDayNames, ["company_id"]);
        
    //     foreach($weekendDayNames as $raw){
    //         $dateListWithoutWeekend = array_filter($keyValueDateList, function($value) use ($raw) {
    //             return $value !== $raw ;
    //         });
    //         $keyValueDateList = $dateListWithoutWeekend ;
    //     }
    //     $dateArray=array_keys($dateListWithoutWeekend );
    //     $jsonData = json_encode($dateArray);
    //     $data=Holiday::find($id);
    //     $data->date = $jsonData;
    //     $data->reason = $request->reason;
    //     $data->company_id = $company_id;
    //     $data->save();

    //     return response()->json([
    //         'message'=> 'Holiday Updated Successfully',
    //         'data'=>$data
    //     ],201);

    // }

    public function HolidayList(){

        $company_id= auth()->user()->company_id;
        $data=Holiday::where('company_id',$company_id)->get();
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
