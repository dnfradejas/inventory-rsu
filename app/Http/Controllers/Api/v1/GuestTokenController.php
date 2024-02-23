<?php

namespace App\Http\Controllers\Api\v1;

use stdClass;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use App\Http\Controllers\Controller;


class GuestTokenController extends Controller
{


    

    public function getGuestToken()
    {

        $custom_claims = [
            'uniqid' => uniqid()
        ];

        $payload = JWTFactory::sub($custom_claims['uniqid'])
                             ->aud('_uniqid')
                             ->_uniqid($custom_claims)
                             ->exp(60)
                             ->make();


        $token = JWTAuth::encode($payload);

        return response()->json([
            'message' => 'OK',
            'token' => $token->get()
        ]);
    }
}
