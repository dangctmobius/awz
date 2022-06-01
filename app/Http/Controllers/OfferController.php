<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OfferController extends Controller
{   

    public function offer_tapjoy(Request $request){

        return $this->responseOk($request->all());

    }
}
