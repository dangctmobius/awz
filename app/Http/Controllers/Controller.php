<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected $retry;

    public function init() {
        $this->retry = 5;
    }

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    protected function responseOK($data, $message='success')
    {
        return response()->json([
            'code'=> 200,
            'name' => "ResponseOK",
            'type' => 'RESPONSE_OK',
            'message'=> $message,
            'data' => $data,
        ], 200);
    }

    protected function responseError($message='error', $code = 500)
    {
        return response()->json([
            'code'=>$code,
            'name' => "ResponseError",
            'type' => 'RESPONSE_ERROR',
            'message'=>$message,
        ], $code);
    }

     /**
     * @param $message
     * @return Response
     */
    protected function respondWithMissingField($message)
    {
        return response()->json([
            'status' => 400,
            'message' => $message,
        ], 400);
    }

    /**
     * @param $message
     * @return Response
     */
    private function respondWithValidationError($message)
    {
        return response()->json([
            'status' => 422,
            'name' => "ResponseError",
            'type' =>"RESPONSE_VALIDATE",
            'message' => $message,
        ], 422);
    }

    /**
     * @param $validator
     * @return Response
     */
    protected function respondWithErrorMessage($validator)
    {
        $required = $messages = [];
        $validatorMessages = $validator->errors()->toArray();
        foreach($validatorMessages as $field => $message) {
            if (strpos($message[0], 'required')) {
                $required[] = $field;
            }

            foreach ($message as $error) {
                $messages[] = $error;
            }
        }

        if (count($required) > 0) {
            $fields = implode(', ', $required);
            $message = "Missing required fields $fields";

            return $this->respondWithMissingField($message);
        }


        return $this->respondWithValidationError(implode(', ', $messages));
    }

    public function checkNull($string)
    {
        return ($string === '' || $string == null);
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

    public function genCode($length = 6) {
        $characters = '123456789ABCDEFGHJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function bscCheckBalance($address = '0xc46E4D514262653245f4d6B99fd7c3C1E4ec055B')
    {
        $response = Http::get('https://api.bscscan.com/api',
        [   
            'module' => 'account',
            'action' => 'tokenbalance',
            'tag' => 'latest',
            'apiKey' => env('BSC_API_KEY'),
            'address' => $address,
            'contractaddress' => env('CONTRACT')
        ]);
        return $response->json();
    }

    public function getPrice(){
        
        $response = Http::get('https://api.pancakeswap.info/api/v2/tokens/'.env('CONTRACT'),);
        $price =  $response->json();
        $price = $price['data']['price'];
        $decimal =  10 ** (int)env('CONTRACT_DEC');
        return (double)$price;
        
        // try {
        //     $response = Http::get('https://api.coingecko.com/api/v3/simple/price?ids=az-world-socialfi&vs_currencies=usd');
        //     $price =  $response->json();
        //     $price = $price['az-world-socialfi']['usd'];
        //     $decimal =  10 ** (int)env('CONTRACT_DEC');
        //     return (double)$price;
        // } catch(\Exception $e){
        //     return 0;
        // }
        

    }
    public function check_vip($address, $retry = 5) {
        
        if ($retry > 0) {
            $response = $this->bscCheckBalance($address);
            if ($response && $response['message'] == 'OK') {
                $data = $response['result'];
                $decimal =  10 ** (int)env('CONTRACT_DEC');
                
                if( ($data / $decimal) >= (int)env('AMOUNT_TOKEN_IS_VIP')) {
                    return $data / $decimal;
                } else {
                    return 0;
                }
            } else if($response['status'] == 'NOTOK'){
                $this->check_vip($address);
                sleep(1);
                $retry--;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
        
    }
}
