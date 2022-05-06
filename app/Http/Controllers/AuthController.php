<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use NextApps\VerificationCode\VerificationCode;
use App\Jobs\SentMailVerify;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    protected $password;

    protected $email_allow;

    public function __construct() {
        $this->middleware(['check_token']);
        $this->password = '170919';
        $this->email_allow = [
        ];
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'pass' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422);
        }

        $email = $request->email;
        if(!$request->changepass)
        {
            $user = User::where('email', $email)->first();
            if ( ! $user) {
            } else {
                return $this->responseError('User already exists!', 201);
            }
        }


        if ( ! in_array($email, $this->email_allow)) {
            \Queue::push(new SentMailVerify($email));
            // VerificationCode::send($email);
            return $this->responseOK(null, 'Sent verification code');
        } else {
            return $this->responseError('Please contact admin for Beta Test!', 201);
        }
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'pass' => 'required|min:6',
            'verify_code' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $code = $request->verify_code;
        $email = $request->email;
        $ref_code = strtoupper($request->ref_code);

        if ( ! in_array($email, $this->email_allow)) {

            if (VerificationCode::verify($code, $email))
            {

                $user = User::where('email', $email)->first();
                if ( ! $user) {
                    User::create(array_merge(
                        $validator->validated(),
                        ['password' => bcrypt($request->pass), 'code' => $this->genCode(6), 'name' => env('APP_NAME').'_'.rand(10000,99999)]
                    ));
                    $user = User::where('email', $email)->first();
                    $user->following()->attach(1);
                } else {
                    return $this->responseError('User already exists!', 201);
                }


                $user = User::where('email', $email)->first();
                if($user) {
                    if($user->ref_code) {


                    } else {
                        // if( $ref_code ) {
                            if ($user->code != $ref_code) {
                                $check_code = User::where('code', $ref_code)->first();
                                if($check_code) {
                                    User::where('id', $user->id)->update(['ref_code' => $ref_code]);
                                    \DB::table('earns')->insert(['user_id' => $check_code->id, 'status' => 2, 'reward' => 1, 'subject' => 'ref', 'description' => 'Reward from referral', 'created_at' => Carbon::now()]);
                                    User::where('id', $user->id)->increment('balance',  1);
                                }
                                //  else {
                                //     return $this->responseError('Invalid referral code', 201);
                                // }
                            }

                        // } else {
                        //     return $this->responseError('Referral code required', 201);
                        // }

                    }
                }

                return $this->responseOK("Register new account success!", 200);

            } else {

                return $this->responseError('Verification code is incorrect', 201);
            }
        } else {
            return $this->responseError('Please contact admin for Beta Test!', 201);
        }

    }


    public function changepass(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'pass' => 'required|min:6',
            'code' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $code = $request->verify_code;
        $email = $request->email;
        $ref_code = strtoupper($request->ref_code);

        if ( ! in_array($email, $this->email_allow)) {

            if (VerificationCode::verify($code, $email))
            {

                $user = User::where('email', $email)->first();
                if ($user) {
                    User::where('email', $email)->update(
                        ['password' => bcrypt($request->pass)]
                    );
                } else {
                    return $this->responseError('The account does not exist!', 201);
                }

                return $this->responseOK("Change new password success!", 200);

            } else {

                return $this->responseError('Verification code is incorrect', 201);
            }
        } else {
            return $this->responseError('Please contact admin for Beta Test!', 201);
        }

    }


    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'pass' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $email = $request->email;

        if ( ! in_array($email, $this->email_allow)) {

                $credentials = $request->only(['email']);

                // $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

                if (! $token = $this->guard()->attempt(['email' => $credentials['email'], 'password' => ($request->pass) ])) {
                    return $this->responseError('Incorrect email or password', 201);
                }

                return $this->respondWithToken($token, Auth::user());


        } else {
            return $this->responseError('Please contact admin for Beta Test!', 201);
        }

    }

    // public function register(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|between:2,100',
    //         'email' => 'required|string|email|max:50|unique:users',
    //         'password' => 'required|string|confirmed|min:6|max:30'
    //     ]);

    //     if($validator->fails()){
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $user = User::create(array_merge(
    //                 $validator->validated(),
    //                 ['password' => bcrypt($request->password)]
    //             ));

    //     return response()->json([
    //         'message' => 'User successfully registered',
    //         'user' => $user
    //     ], 201);
    // }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function guard() {
        return Auth::guard('api');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }


    public function changePassWord(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $userId = auth()->user()->id;

        $user = User::where('id', $userId)->update(
                    ['password' => bcrypt($request->new_password)]
                );

        return response()->json([
            'message' => 'User successfully changed password',
            'user' => $user,
        ], 201);
    }
}
