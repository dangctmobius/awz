<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kyc;

class KycController extends Controller
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
    public function index()
    {
        //
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
         $validator =   \Validator::make($request->all(), [
            'name_owner' => 'required',
            'telegram' => 'required',
            'name_project' => 'required',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $kyc = new Kyc;
        $kyc->name_owner = $request->name_owner;
        $kyc->telegram = $request->telegram;
        $kyc->name_project = $request->name_project;
        $kyc->user_id = $this->user->id;
        $kyc->is_kyc = $request->is_kyc;
        $kyc->is_audit = $request->is_audit;
        $kyc->save();

        if($kyc){
            return $this->responseOK($kyc, 'success');
         }else{
            return $this->responseError();
         } 

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
