<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{


    public function handle(Request $request, Closure $next)
    {   
        $time_cache = 5;
        $time_request = $request->time_request;
        $code = $request->code;
        $now = \Carbon\Carbon::now()->timestamp;
        $driff = ($now - ($time_request/10**6)) / 60;
        if($driff > $time_cache) {
            echo json_encode(['error' => 'code 22']);die;
        }
        if (isset($time_request) && md5(md5(env('SECURITY_CODE').$time_request)) === $code)
        {  
             if(!env('APP_DEBUG')) {
                // $token = \DB::table('token_requests')->where('token', $code)->count();
                $token = \Cache::get($code);
                if ($token) {
                    echo json_encode(['error' => 'code 20']);die;
                }
                $token = \Cache::remember($code, 60*$time_cache, function () use($time_request) {
                    return $time_request;
                 });
                // \DB::table('token_requests')->insert(['token' => $code, 'timestamp' => $time_request, 'created_at' => time(), 'ip' => $this->getIp()]);
            }

            if (\Auth::check()) {
                $user = auth()->user();
                if ($user->is_ban) {
                    return redirect()->route('login');
                    abort(403);        
                }
            }
          
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
