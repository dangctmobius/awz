<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OfferController extends Controller
{   

    public function offer_tapjoy(Request $request){
        
        $req_dump = print_r($request->all(), TRUE);
        $fp = fopen('request.log', 'a');
        fwrite($fp, $req_dump);
        fclose($fp);

        return $this->responseOk($request->all());

    }
}
