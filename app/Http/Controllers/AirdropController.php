<?php

namespace App\Http\Controllers;

use App\Models\Airdrop;
use Illuminate\Http\Request;

class AirdropController extends Controller
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
        $data['total'] = Airdrop::count();
        if($request->type && $request->type=='history') {
            $airdrops = Airdrop::withCount('submits')->with(['submits' => function ($q) use($user_id) {
                $q->where('airdrop_submits.user_id', $user_id);
           }])->skip($page*$limit )->take($limit)->get();
        } else {
            $airdrops = Airdrop::where('status', 1)->withCount('submits')->with(['submits' => function ($q) use($user_id) {
                $q->where('airdrop_submits.user_id', $user_id);
           }])->skip($page*$limit )->take($limit)->get();
        }
       
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $airdrops;
        return $this->responseOK($data);
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
     * @param  \App\Models\Airdrop  $airdrop
     * @return \Illuminate\Http\Response
     */
    public function show(Airdrop $airdrop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Airdrop  $airdrop
     * @return \Illuminate\Http\Response
     */
    public function edit(Airdrop $airdrop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Airdrop  $airdrop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airdrop $airdrop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Airdrop  $airdrop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Airdrop $airdrop)
    {
        //
    }
}
