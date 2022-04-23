<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $data = [
            [
                'id' => 1,
                'symbol' => 'CRONOAPE',
                'address' => '0x06eb438c7fad0a49ce63235f7ee41f7dbc9b9db5'
            ],
            [
                'id' => 2,
                'symbol' => 'SAFUU',
                'address' => '0xe5ba47fd94cb645ba4119222e34fb33f59c7cd90'
            ],
            [
                'id' => 3,
                'symbol' => 'RACA',
                'address' => '0x12bb890508c125661e03b09ec06e404bc9289040'
            ],
            [
                'id' => 4,
                'symbol' => 'BabyDoge',
                'address' => '0xc748673057861a797275CD8A068AbB95A902e8de'
            ],
            [
                'id' => 5,
                'symbol' => 'ZORO',
                'address' => '0x05ad901cf196cbDCEaB3F8e602a47AAdB1a2e69d'
            ],
            
        ];

        return $this->responseOK(['items' => $data], 'success');
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
