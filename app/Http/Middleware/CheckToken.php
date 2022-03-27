<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{


    public function handle(Request $request, Closure $next)
    {   
        $time_request = $request->time_request;
        $code = $request->code;
        if (isset($time_request) && md5(md5(env('SECURITY_CODE') . env('APP_VERSION') .$time_request)) === $code)
        {   
            // $token = \DB::table('token_requests')->where('token', $code)->count();
            // if ($token > 0) {
            //     echo json_encode(['error' => 'code 20']);die;
            // }
            // \DB::table('token_requests')->insert(['token' => $code, 'timestamp' => $time_request, 'created_at' => time(), 'ip' => $this->getIp()]);
            return $next($request);

        } else {
           
            echo json_encode(['error' => 'code 21']);die;
        }
    }
    
    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

}
