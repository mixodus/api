<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class UserToken
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
        $header = $request->header('X-Api-Key');
        if($header != "idstar123!"){
            $response = [
                'status' 	=> false,
                'message' 	=> 'Unauthorized Access, invalid api key'
			];
            return response()->json($response, 401);
        }

        $token = $request->header('X-Token');
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'status' => false,
                'message' => 'X-Token not provided.'
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, 'X-Api-Key', array('HS256'));
            
        } catch(ExpiredException $e) {
            return response()->json([
                'status' => 402,
                'message' => 'Provided token is expired.'
            ], 402);
        } catch(Exception $e) {
            return response()->json([
                'status' => (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500,
                'message' => 'Auth - '.$e->getMessage().' - '.$e->getFile().' - L '.$e->getLine()
            ],(method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
        return $next($request);

    }
}
