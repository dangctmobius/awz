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
        $data = [
            'version' => env('APP_VERSION'),
            'home_banner' => 'https://cdn.azworld.network/icon.gif',
            // 'home_banner' => 'https://cdn.azworld.network/close_testnet.jpg',
            'member_banner' => 'https://1014081465-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F6SJlk6stT2h3qjzcShZJ%2Fuploads%2F3EDIVnicUoA5AZWkRVFa%2Fmember.jpg?alt=media&token=20300823-a7a6-4e64-85b4-365d1147959e',
            'symbol' => '$AZW',
            'min_vip' => env('AMOUNT_TOKEN_IS_VIP') . '+',
            'page_ref_text' => 'Send a referral link to your friend\nIf the people you refer go shopping - You will get up to 20% Cashback commission  in  that order\nMax 5 users / day',
            'page_ref_how_it_work' => 'A referral program is a system that incentivizes previous customers to recommend your products to their family and friends. Retail stores create their own referral programs as a way to reach more people. It\'s a marketing strategy that asks previous happy, loyal customers to become brand advocates',
            'page_wheel_text' => 'The total value of the payout pool is '.env('POOL').' AZW, which will decrease after each spin. When you spin how many USDT, we will send you the corresponding token according to the market price',
            'home_no_data_earning_today' => 'You have not earned AZW token today, or quickly get rewarded by referring friends or using lucky wheel, earn money by reading news, watching ads',
            'page_withdraw' => 'You can withdraw point to AZW token. \nIf you don\'t have a wallet, create and add AZW tokens to your account. \nConversation: 1 AZW offchain = 1 AZW onchain.\nYou must keep AZW tokens in your wallet continuously for 7 days until the withdrawal from the app is approved. We will refuse to withdraw funds if we detect fraud.',
            'amount_bet_dice' => env('AMOUNT_BET_DICE'),
            'page_offer' => 'When you do the task you will be paid AZW tokens, up to 10,000 AWZ. 10 Point in task = 1 AZW',
            'min_withdraw' => (int)env('MIN_WITHDRAW') ?? 1000,
            'earn_status' => [
                [   
                    'value' => 1,
                    'label' => 'Processing',
                    'color' => '#ffe58f'
                ],
                [   
                    'value' => 2,
                    'label' => 'Available',
                    'color' => '#52c41a'
                ],
                [   
                    'value' => 3,
                    'label' => 'Cancel',
                    'color' => '#ffe58f'
                ],
                [   
                    'value' => 4,
                    'label' => 'Withdrawn',
                    'color' => '#ffe58f'
                ],
                [   
                    'value' => 5,
                    'label' => 'Withdrawing',
                    'color' => '#ffe58f'
                ],
                [   
                    'value' => 6,
                    'label' => 'Freeze',
                    'color' => '#ffe58f'
                ],
            ],
            
        ];

        return $this->responseOK($data, 'success');
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
        $data = env("HOME_NOTICE");

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
