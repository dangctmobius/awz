<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Earn;
use Illuminate\Support\Carbon;

class AdsController extends Controller
{
    private $user;
    public function __construct()
    {   
        $this->middleware(['check_token']);
        $this->user = auth()->user();
    }

    public function limit()
    {   
        $limit = MAX_VIP_REWARD_ADS;
        if($limit){
            return $this->responseOK((int)$limit, 'success');
         }else{
            return $this->responseError();
         }
    }

    public function earn(Request $request)
    {   

        // $validator = \Validator::make($request->all(), [
        //     'g-recaptcha-response' => 'required|recaptcha'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }
        $task_id = $request->task_id;
        $user_id = $this->user->id;
        $address = $this->user->address;
        // dd($address);
        
        if($address)
        {   
            $balance = $this->check_vip($address);
            if($balance)
            {
                $total_earn = Earn::where('user_id', $user_id)->where('subject', 'ads')->whereDate('created_at', Carbon::today())->count();
                if($total_earn < (int)env('LIMIT_ADS_VIDEO')) {
                    $reward = (int)env('POINT_REWARD_ADS');
                    if(true){
                        $history = Earn::insert(['user_id' => $user_id, 'status' => 2, 'reward' => $reward, 'subject' => 'ads', 'description' => 'Reward from ads', 'created_at' => Carbon::now()]);
                        User::where('id', $user_id)->increment('balance', $reward);
                        return $this->responseOK(true, 'success');
                    }else{
                        return $this->responseError();
                    }
                } else {
                    return $this->responseError('You watched max daily.', 200);
                }
            } else {
                return $this->responseError('You\'re not a VIP member.', 200);
            }
            
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
        
    }
    
    public function check_show_ads(Request $request)
    {
        $task_id = $request->task_id;
        $user_id = $this->user->id;
        $address = $this->user->address;
        if($address && $this->check_vip($address))
        {   
            $total_earn = Earn::where('user_id', $user_id)->where('subject', 'ads')->whereDate('created_at', Carbon::today())->count();
            
            if($total_earn < MAX_VIP_CLICK_TASK)
            { 
                    return $this->responseOK(['allow_show_ads' => 1], 'success');

            } else {
                return $this->responseError('You watched max daily.', 200);
            }
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
    }
}
