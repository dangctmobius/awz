<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
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
        if(($address &&  $this->check_vip($address)))
        {   
            $total_earn = \DB::table('user_ptc_task')->where('user_id', $user_id)->count();
            if($total_earn < (int)env('MAX_VIP_CLICK_TASK')) {
                
                $earn = \DB::table('user_ptc_task')->insert(['task_id' => $task_id, 'user_id' => $user_id, 'created_at' => Carbon::now()]);
                $reward = Task::where('id', $task_id)->first();
                $reward = $reward->reward;
                if($earn){
                    $history = \DB::table('earns')->insert(['user_id' => $user_id, 'status' => 1, 'reward' => $reward, 'subject' => 'tasks', 'description' => 'Reward from ptc', 'created_at' => Carbon::now()]);
                    User::where('id', $user_id)->increment('balance', $reward);
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
