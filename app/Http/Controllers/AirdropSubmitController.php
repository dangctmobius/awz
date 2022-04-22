<?php

namespace App\Http\Controllers;

use App\Models\AirdropSubmit;
use Illuminate\Http\Request;

class AirdropSubmitController extends Controller
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
    public function submit(Request $request)
    {
        $rules = [
            'twitter_username'   => 'required',
            'telegram_username'   => 'required',
            'wallet'   => 'required',
            'airdrop_id'   => 'required',
        ];
        $messages = [
            'twitter_username.required'   => __('Twitter username require'),
            'telegram_username.required'   => __('Telegrame username require'),
            'wallet.required'   => __('Wallet require'),
            'airdrop_id.required'   => __('Airdrop id require'),
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

        $submit = new AirdropSubmit();
        $submit->user_id = $this->user->id;
        $submit->airdrop_id = $request->airdrop_id;
        $submit->twitter_username = $request->twitter_username;
        $submit->telegram_username = $request->telegram_username;
        $submit->wallet = $request->wallet;
        $submit->status = 1;
        $submit->save();

        if($submit) {
            return $this->responseOK($submit, 'success');
        } else {
            return $this->responseError();
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
     * @param  \App\Models\airdrop_submit  $airdrop_submit
     * @return \Illuminate\Http\Response
     */
    public function show(airdrop_submit $airdrop_submit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\airdrop_submit  $airdrop_submit
     * @return \Illuminate\Http\Response
     */
    public function edit(airdrop_submit $airdrop_submit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\airdrop_submit  $airdrop_submit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, airdrop_submit $airdrop_submit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\airdrop_submit  $airdrop_submit
     * @return \Illuminate\Http\Response
     */
    public function destroy(airdrop_submit $airdrop_submit)
    {
        //
    }
}
