<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Earn;
use App\Models\User;
use Illuminate\Support\Carbon;

class OfferController extends Controller
{   


   

    public function offer_tapjoy(Request $request){

        return $this->reponseOk($request->all());

    }
}
