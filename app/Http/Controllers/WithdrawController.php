<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use App\Models\User;
use Illuminate\Http\Request;


class WithdrawController extends Controller
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
    public function withdraw(Request $request)
    {
        $amount = $request->amount;
        if((double)$this->user->balance >= (double)$amount)
        {
            $withdraw = new Withdraw();
            $withdraw->user_id = $this->user->id;
            $withdraw->amount = $request->amount;
            $withdraw->status = 1;
            $withdraw->description = 'Withdraw processing';
            $withdraw->save();    
            $user = User::where('id', $this->user->id)->first();
            if($user->balance >= $amount) {
                User::where('id', $this->user->id)->decrement('balance',  $amount);
                User::where('id', $this->user->id)->increment('frozen',  $amount);
                return $this->responseOK($withdraw, 'success');
            } else {
                return $this->responseError('You not enough balance');
            }
        } else {
            return $this->responseError('You not enough balance');
        }
       
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {   

        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $user_id = $this->user->id;
        $status = $request->status ?? 1;

        // if($status == 1) {
        //     $statuss = ['];
        // } else if ($status == 2)  {
        //     $statuss = ['tasks'];
        // }else {
        //     $statuss = [];
        // }

        $comments = Withdraw::where('user_id', $user_id)->whereStatus($status)->orderBy('id','desc')->skip($page*$limit )->take($limit)->get();
        
        $data = [];
        $data['count'] = Withdraw::where('user_id')->count();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $comments;
        return $this->responseOK($data);
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
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show(Withdraw $withdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdraw $withdraw)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdraw $withdraw)
    {
        //
    }
}
