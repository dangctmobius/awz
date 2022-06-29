<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Earn;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    private $user;
    public function __construct()
    {   
        $this->middleware(['check_token']);
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $category = $request->category ? (int)$request->category : 1;
        $user_id = $this->user->id;
        $data = [];
        $subject = $request->subject;
        $data['total'] = Task::count();
        $products = Task::where('status', 1)->where('category', $category)->where('subject', $subject)->orderBy('id', 'desc')->with(['user' => function ($q) use($user_id) {
                $q->where('user_ptc_task.user_id', $user_id);
        }])->skip($page*$limit )->take($limit)->get();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $products;
        return $this->responseOK($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function earn(Request $request)
    {
        $task_id = $request->task_id;
        $user_id = $this->user->id;

        $address = $this->user->address;
        $allow = false;
        $now = Carbon::now();
        // if(!$this->user->is_vip){
        //     return $this->responseError('You are not in Mainnet List', 200);
        // }
        if(($address &&  $this->check_vip($address)))
        {   

            $now = Carbon::now();

            $after = 15; //minute
            // echo (Carbon::now()->toDateTimeString());
            $date = Carbon::now()->subMinutes($after);

            $total_earn = \DB::table('user_ptc_task')->where('user_id', $user_id)->count();
            $total_earn2 = Earn::where('user_id', $user_id)->where('subject', 'tasks')->where('created_at' , '>=', $date)->count();

            if($total_earn2 > 0) {
                
                $earn = Earn::where('user_id', $user_id)->where('subject', 'tasks')->where('created_at' , '>=', $date)->orderBy('id', 'desc')->first();

                $created_at = ($earn->created_at->toDateTimeString());

                $count_down = $after  -  Carbon::parse($created_at)->diffInMinutes(Carbon::now());

                // $count_down = floor($count_down / 60).'h'.($count_down -   floor($count_down / 60) * 60);

                return $this->responseError('You can earn every '.$after.' minutes. After '.$count_down . 'm', 200);
            }
            if($total_earn < (int)env('MAX_VIP_CLICK_TASK')) {
                
                $earn = \DB::table('user_ptc_task')->insert(['task_id' => $task_id, 'user_id' => $user_id, 'created_at' => $now]);
                $reward = Task::where('id', $task_id)->first();
                $reward = $reward->reward;
                $price = $this->getPrice();
                $reward =  (double)env('POINT_REWARD_TASK') / $price;
                $reward = intval($reward);
                $key =  $user_id.'_'.$now;
                if($earn){
                    $history = \DB::table('earns')->insert(['user_id' => $user_id, 'status' => 1, 'reward' => $reward, 'subject' => 'tasks', 'description' => 'Reward from ptc', 'created_at' => $now, 'key' => $key]);
                    User::where('id', $user_id)->increment('pending_balance', $reward);
                    return $this->responseOK(true, 'success');
                }else{
                    return $this->responseError();
                }
            } else {
                return $this->responseError('You cliked max daily.', 200);
            }
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
