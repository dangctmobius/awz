<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Offer;
use App\Models\User;

class OfferController extends Controller
{   

    public function offer_tapjoy(Request $request){
        
        // Array
        // (
        //     [currency] => 3
        //     [display_multiplier] => 1.0
        //     [id] => 830c243a-44df-47e3-8610-2a479b0c81e4
        //     [mac_address] => 
        //     [snuid] => user_id123
        //     [verifier] => aff3876b856ca0ff45f4e6e7435c0924
        // )
        $user_id = $request->snuid;
        

        if($request->id) {
            $user = User::where('id', $user_id)->first();
            $price = $this->getPrice();
            $reward = (double)env('REWARD_OFFER') * $request->currency;
            $reward = $reward / $price;

            $reward = intval($reward);


            $offer = new Offer();
            $offer->provider = 'tapjoy';
            $offer->currency = $reward;
            $offer->ads_id = $request->id;
            $offer->user_id = $request->snuid;
            $offer->verifier = $request->verifier;
            $offer->save();
            
            $des = 'Reward from offer: '.$request->id;
            if($user->address) {
                $balance = $this->check_vip($user->address);
                if($balance >= (int)env('AMOUNT_TOKEN_IS_VIP')){
                    
                } else {
                    $reward = 10;
                    $des = 'Reward for free member';
                } 
            } else {
                $reward = 10;
                $des = 'Reward for free member';
            } 
            
            
            if ($offer) {
                $history = \DB::table('earns')->insert(['user_id' => $user_id, 'status' => 1, 'reward' => $reward, 'subject' => 'earn_offer', 'description' => $des, 'created_at' => Carbon::now()]);
                User::where('id', $user_id)->increment('pending_balance',  $reward);
            }
        }

        
       
        $req_dump = print_r($_REQUEST, TRUE);
        $fp = fopen('request.log', 'a');
        fwrite($fp, $req_dump);
        fclose($fp);

        return $this->responseOk($request->all());

    }
}
