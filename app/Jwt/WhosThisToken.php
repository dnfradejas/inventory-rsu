<?php

namespace App\Jwt;

use stdClass;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Facades\JWTAuth;

class WhosThisToken
{


    /**
     * Get identity based on jwt token
     *
     * @return \stdClass|\App\Models\User
     */
    public function getIdentity()
    {

        $token = JWTAuth::getToken();
        
        $token = $token instanceof Token ? $token : new Token($token);
        $jwt = JWTAuth::decode($token);
        $guestId = $jwt->get('_uniqid');
        if ($guestId) {
            $guest = new stdClass();
            $guest->id = $guestId->uniqid;
            return $guest;
        }

        return auth()->user();
    }
}