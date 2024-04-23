<?php

namespace App\Http\Controllers\frontendController;
use App\Models\Expenses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon; 
use App\Utils\BaseUrl;

class expenseController extends Controller
{
    public function expenseList(){
        $access_token = session('access_token');
        $baseUrl = BaseUrl::get();

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl.'/all-expenses-list',
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
        return view('frontend.expense',compact('dataArray'), ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

    public function expensesReport(){
        $access_token = session('access_token');
        $baseUrl = BaseUrl::get();
        return view('frontend.expensesReport', ['jwtToken' => $access_token,'baseUrl' => $baseUrl]);
    }

    public function individualExpenseReport($id,$startDate,$endDate){
        
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $result = Expenses::where('expenses.emp_id',$id)
                        ->join("employees","employees.emp_id","=","expenses.emp_id")
                        ->join("expenses_catagories","expenses.expenses_catagories_id","=","expenses_catagories.expenses_catagories_id")
                        ->whereBetween('expenses.created_at', [$startDate, $endDate])
                        ->where('expenses.status','=','approved')
                        ->get(['employees.*','expenses.*','expenses_catagories.catagory']);
                        
        return view('frontend.individualExpenseReport',compact('result'));
    }
}
