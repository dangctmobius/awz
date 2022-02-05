<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
}
