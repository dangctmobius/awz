<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Earn;
use Illuminate\Support\Carbon;

class EarnController extends Controller
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
        $subject = $request->subject;
        $user_id = $this->user->id;
        $subjects = [];
        
        if($subject == 1) {
            $subjects = ['tasks', 'spin', 'ads', 'donate', 'ref'];
        } else if ($subject == 2)  {
            $subjects = ['tasks'];
        } else if ($subject == 3) {
            $subjects = ['ads'];
        } else if ($subject == 4) {
            $subjects = ['spin'];
        } else if ($subject == 5) {
            $subjects = ['donate'];
        } else if ($subject == 6) {
            $subjects = ['ref'];
        } else {
            $subjects = [];
        }
        $data = [];
        $data['total'] = Earn::count();
        $products = Earn::where('user_id', $user_id)->where('status', 2)->whereIn('subject', $subjects)->skip($page*$limit)->orderBy('id', 'desc')->take($limit)->get();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $products;
        return $this->responseOK($data);
    }

    public function list_today(Request $request)
    {
        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $category = $request->category ? (int)$request->category : 1;
        $subject = $request->subject;
        $user_id = $this->user->id;
        
        if($subject == 1) {
            $subjects = ['tasks', 'spin', 'ads', 'donate', 'ref'];
        } else if ($subject == 2)  {
            $subjects = ['tasks'];
        } else if ($subject == 3) {
            $subjects = ['ads'];
        } else if ($subject == 4) {
            $subjects = ['spin'];
        } else if ($subject == 5) {
            $subjects = ['donate'];
        } else if ($subject == 6) {
            $subjects = ['ref'];
        } else {
            $subjects = [];
        }
        $data = [];
        $data['total'] = Earn::count();
        $products = Earn::where('user_id', $user_id)->where('status', 2)->whereIn('subject', ['tasks', 'spin', 'ads', 'ref',])->whereDate('created_at', '=', Carbon::today())->skip($page*$limit)->orderBy('id', 'desc')->take($limit)->get();
        $total_earn_today = Earn::where('user_id', $user_id)->where('status', 2)->whereIn('subject', ['tasks', 'spin', 'ads', 'ref'])->whereDate('created_at', '=', Carbon::today())->sum('reward');
        foreach($products as &$product) {
            $product['total_earn'] = $total_earn_today;
        }
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $products;
        return $this->responseOK($data);
    }

    public function list_chart(Request $request)
    {
        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $category = $request->category ? (int)$request->category : 1;
        $subject = $request->subject;
        $user_id = $this->user->id;
        
        if($subject == 1) {
            $subjects = ['tasks', 'spin', 'ads', 'donate', 'ref'];
        } else if ($subject == 2)  {
            $subjects = ['tasks'];
        } else if ($subject == 3) {
            $subjects = ['ads'];
        } else if ($subject == 4) {
            $subjects = ['spin'];
        } else if ($subject == 5) {
            $subjects = ['donate'];
        } else if ($subject == 6) {
            $subjects = ['ref'];
        } else {
            $subjects = [];
        }
        $data = [];
        
        $visitorTraffic = Earn::where('created_at', '>=', Carbon::now()->subMonth())->whereStatus(2)->where('user_id', $this->user->id)->groupBy('date')->orderBy('date', 'DESC')->limit(7)->get(array(
            \DB::raw('Date(created_at) as date'),
            \DB::raw('COUNT(*) as "reward"'),
        ));

        
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $visitorTraffic;
        return $this->responseOK($data);
    }


   

    public function earn_total(Request $request)
    {   
        $user_id = $this->user->id;
        $data = [];
        $data['total'] = Earn::count();
        $total = Earn::where('user_id', $user_id)->where('status', 2)->sum('reward');
        $data['total'] = bcadd($total, '0', 4);
        return $this->responseOK($data);
    }

    public function earn(Request $request)
    {
        $task_id = $request->task_id;
        $user_id = $this->user->id;

        if(!$this->user->address)
        {   
            $total_earn = \DB::table('earns')->where('user_id', $user_id)->whereDate('created_at', '>=', \Carbon::today())->count();
            if($total_earn < (int)env('MAX_VIP_CLICK_TASK')) {
                $earn = \DB::table('user_ptc_task')->insert(['task_id' => $task_id, 'user_id' => $user_id, 'created_at' => time()]);
                $reward = Task::where('id', $task_id)->first();
                $reward = $reward->reward;
                if($earn){
                    $history = \DB::table('earns')->insert(['user_id' => $user_id, 'status' => 2, 'reward' => $reward, 'subject' => 'tasks', 'description' => 'Reward from ptc', 'created_at' => time()]);
                    User::where('id', $user_id)->increment('balance', $reward);
                    return $this->responseOK(null, 'success');
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
