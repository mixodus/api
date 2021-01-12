<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

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
            return response()->json($response, 406);
        }

        $token = $request->header('X-Token');
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'status' => false,
                'message' => 'Anda harus login ke aplikasi untuk menggunakan fitur ini.'
            ], 406);
        }
        try {
            $credentials = JWT::decode($token, 'X-Api-Key', array('HS256'));
            
        }catch(SignatureInvalidException $e) {
            $data["classname"]= "Firebase\\JWT\\SignatureInvalidException";
            return response()->json([
                'status' => false,
                'message' => 'Provided Token is Expired',
                'error'=> $data
            ], 406);
        } 
        catch(ExpiredException $e) {
            $data["classname"]= "Firebase\\JWT\\SignatureInvalidException";
            return response()->json([
                'status' => false,
                'message' => 'Signature verification failed',
                'error'=> $data
            ], 406);
        } catch(Exception $e) {
            return response()->json([
                'status' => (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500,
                'message' => 'Auth - '.$e->getMessage().' - '.$e->getFile().' - L '.$e->getLine()
            ],(method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
        return $next($request);

    }
}
