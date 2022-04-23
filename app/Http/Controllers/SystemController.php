<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
class SystemController extends Controller
{   



    public function __construct() {
        $this->middleware(['api_throttle:10,1']);
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
    public function app_version()
    {
        return $this->responseOK(env('APP_VERSION'), 'success');
    }

    public function guest_token()
    {
        return $this->responseOK(env('GUEST_TOKEN'), 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allow_function(Request $request)
    {
        $data = [
            'cashback' => 1,
            'ptc' => 1,
        ];

        return $this->responseOK($data, 'success');
    }

    public function home_alert(Request $request)
    {
        $data = '';

        return $this->responseOK($data, 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function currency()
    {   
        $currencies = Currency::where('status', 1)->get();
       
        if($currencies) {
            return $this->responseOK(['items' => $currencies], 'success');
        } 
        return $this->responseError();


        
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
