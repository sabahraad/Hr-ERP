<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function unauthorized(){
        return response()->json([
            'message' => 'Please Enter a Valid Access Token',
            'status' => Response::HTTP_UNAUTHORIZED,
            'error' => 'Invalid Token'


        ],Response::HTTP_UNAUTHORIZED);
    }
}
