<?php

namespace App\Http\Middleware\Api;

use Closure;

use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        try{
            // https://medium.com/@3lpsy/apis-and-laravel-part-2-jwt-tymon-3e7f365af81c
            $token = JWTAuth::getToken();
            JWTAuth::authenticate($token);
        } catch(TokenExpiredException $e){
            try {
                $new_token = JWTAuth::refresh($token);
                return response()->json([
                    'message' => 'You are using an old access token. Please use recently refreshed token.',
                    'refreshed_token' => $new_token,
                ], 401);

            } catch (TokenBlacklistedException $e) {
                return response()->json([
                    'message' => 'Oops. The token you are using has been blocklisted. Please request a new one!'
                ], 400);
            }
        } catch(JWTException $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Internal server error!'
            ], 500);
        }
        
        return $next($request);
    }
}
