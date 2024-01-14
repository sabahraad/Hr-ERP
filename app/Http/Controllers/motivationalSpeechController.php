<?php

namespace App\Http\Controllers;

use App\Models\Motivation;
use Illuminate\Http\Request;

class motivationalSpeechController extends Controller
{
    public function motivationalSpeech(){
        $response = Motivation::inRandomOrder()->first();
        return response()->json([
            'message' =>  'Motivational Speech',
            'data'=>$response
        ],200);
    }

    public function saveMotivationalQuote(Request $request){
            $data = new Motivation();
            $data->quote = $request->quote;
            $data->author = $request->author;
            $data->save();

            return response()->json([
                'message'=>'Quote added',
                'data'=>$data
            ],201);
    }
}
