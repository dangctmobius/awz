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
    public function app_version(Request $request)
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
            'page_wheel_text' => 'The total value of the payout pool is '.env('POOL').' AZW, which will decrease after each spin. We will send you the corresponding token according to the market price. Note: Lucky Wheel is extra function of AZ World APP It is auto-balance system ( Volume - Reward Pool - Users ) : Time to spin now is '.(int)env('SPIN_TIME').' hours',
            'home_no_data_earning_today' => 'You have not earned AZW token today, or quickly get rewarded by referring friends or using lucky wheel, earn money by reading news, watching ads',
            'page_withdraw' => '- Pending : 20 - 25 days\n- System will check and send $ to you. We will refuse to withdraw funds if we detect fraud.\nIf you want a short-term investment, please create another wallet for trade. Please do not move or AZW transaction in your wallet. We refuse to withdraw the money if we find out that action.\n 1 Device = Use 1 Email + 1 Wallet!',
            'amount_bet_dice' => env('AMOUNT_BET_DICE'),
            'page_offer' => 'When you do the task you will be paid AZW tokens, up to 10,000 AZW. 5 Point in task = 1 AZW',
            'setting_page' => 'Note: 1 Email only connects with 1 Wallet',
            'page_withdrawal_help' => 'Step 1: Connect your wallet\nStep 2: You must make sure you have purchased the AZW Token\nStep 3: You can visit the website azworld.network for instructions to buy Tokens\nStep 4: You must hold AZW to use the monetization functions',
            'page_member_main' => '1.  Connect your wallet\n2. Hold '.env('AMOUNT_TOKEN_IS_VIP').'AZW in wallet\n3.  Membership upgrade confirmation\n- VIP 3 - Vip 5\n- Super Vip 1 -> Super Vip 5 ( Coming Soon )',
            'page_member_help' => 'Step 1: Connect your wallet\nStep 2: You must make sure you have purchased the AZW Token\nStep 3: You can visit the website azworld.network for instructions to buy Tokens\nStep 4: You agree that wallet only see balance\nStep 5: We confirm you have been promoted to membership\nStep 6: You must hold AZW to use the monetization functions',
            'page_home' => '👉 If you see Processing  - Our partners and system are checking it 1/ Pending —> 2/ Offchain —> 3/ Withdrawal —> 4/ Onchain ( To Wallet ). VIP1: '.env('AMOUNT_TOKEN_IS_VIP').'. VIP2: '.env('AMOUNT_TOKEN_IS_VIP1'),
            'is_show_banner' => 0,
            'is_show_video' => 1,
            'maintenance' => env('MAINTENANCE'),
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
                    'color' => '#df1010'
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
            'menu' => [
                [
                    'title' => 'Paid To Click - Read to earn',
                    'sub_title' => '- You  click  Ads or Read to earn - You will earn $\n- Limited click per day | Earn 80% Revenue'
                ],
                [
                    'title' => 'Short Link To Earn (Soon)',
                    'sub_title' => '- Shorten your long link ( documents...) and share'
                ],
                [
                    'title' => 'Shopping Cashback',
                    'sub_title' => '- Get Cash Back from 2000+ stores on the brands you love\n- Get Coupon code + The Cashback up to 30% when go shopping'
                ],
                [
                    'title' => 'Lucky Wheel - Lottery',
                    'sub_title' => '- Only Holder can spin per day from Ads Fund\n- Lottery system we will update with VRF Chainlink'
                ],
                [
                    'title' => 'Withdraw Money',
                    'sub_title' => '- Withdraw the money you earn easily'
                ],
                [
                    'title' => 'Live Channel',
                    'sub_title' => '- Where fans can support celebrities with $AZW tokens\n- Channel with exclusive benefit'
                ],
                [
                    'title' => 'Referral',
                    'sub_title' => '- Share the link or code, you will get Free AZW\n- Get up to 20% Commission of their Shopping Cashback'
                ],
                [
                    'title' => 'AZ Shop | E-product',
                    'sub_title' => '- You can buy e-products with special Price - Discount from Partners\n- Use \$AZW - simply swipe and everything will be sent to email'
                ],
                [
                    'title' => 'Offers - Install app - Surveys',
                    'sub_title' => '- Click here to get more rewards'
                ],
                [
                    'title' => 'Member',
                    'sub_title' => ''
                ],

            ],
            'product_ids' => [
                'subscription_silver',
                'subscription_gold',
                'donate_for_azworld'
            ],
            'withdraw_processing_time' => 'System checking',
            'withdraw_fee' => 'Currently: Free - Burn 0%',
            'enable_chart' => 1,
            'chart_api' => 'https://api.coingecko.com/api/v3/coins/az-world-socialfi/ohlc?vs_currency=usd&days=7',
            'lottery_exp_time' => 1561198600000,
            
        ];

        if ($request->lang && $request->lang == 'zh'){

            $data = [
                'version' => env('APP_VERSION'),
                'home_banner' => 'https://cdn.azworld.network/icon.gif',
                // 'home_banner' => 'https://cdn.azworld.network/close_testnet.jpg',
                'member_banner' => 'https://1014081465-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F6SJlk6stT2h3qjzcShZJ%2Fuploads%2F3EDIVnicUoA5AZWkRVFa%2Fmember.jpg?alt=media&token=20300823-a7a6-4e64-85b4-365d1147959e',
                'symbol' => '$AZW',
                'min_vip' => env('AMOUNT_TOKEN_IS_VIP') . '+',
                'page_ref_text' => '向您的朋友发送推荐链接 \n 如果您推荐的人去购物 - 您将在该订单中获得高达 20% 的返现佣金 \n 最多 5 个用户/天',
                'page_ref_how_it_work' => '推荐计划是一种激励以前的客户向他们的家人和朋友推荐您的产品的系统。零售商店创建了自己的推荐计划，以吸引更多人。这是一种营销策略，要求以前快乐、忠诚的客户成为品牌拥护者',
                'page_wheel_text' => '支付池的总价值是 '.env('POOL').' AZW，每次旋转后都会减少。 我们将根据市场价格向您发送相应的代币。 注意: 幸运轮是AZ World APP的额外功能它是自动平衡系统 (数量-奖励池-用户）: 现在旋转时间是'.(int)env('SPIN_TIME').'小时',
                'home_no_data_earning_today' => '您今天还没有获得AZW令牌, 或者通过推荐朋友或使用幸运轮快速获得奖励，通过阅读新闻，看广告赚钱',
                'page_withdraw' => '您可以将积分提取到 AZW 代币。\n如果您没有钱包，请创建 AZW 代币并将其添加到您的帐户。 转换：1 AZW 链下 = 1 AZW 链上。\n- 您必须持有 > 7 天 - 系统将检查并发送 $ 给您。 如果我们发现欺诈行为，我们将拒绝提取资金。\n如果您想进行短期投资，请创建另一个用于交易的钱包。 请不要在您的钱包中移动或进行 AZW 交易。 如果我们发现该行为，我们将拒绝取款。\n1 Device = Use 1 Email + 1 Wallet !',
                'amount_bet_dice' => env('AMOUNT_BET_DICE'),
                'page_offer' => '当您完成任务时，您将获得 AZW 代币，最高 10,000 AZW 任务点 = 1 AZW',
                'setting_page' => '注意: 1 封电子邮件仅与 1 个钱包连接 - 您可以将其更改为系统上的反垃圾邮件',
                'page_withdrawal_help' => '第 1 步：连接您的钱包\n第 2 步: 您必须确保您已购买 AZW 代币\n第 3 步: 您可以访问网站 azworld.network 获取购买代币的说明\n第 4 步: 您同意钱包只看到余额\n第 5 步: 我们确认您已晋升为会员\n步骤 6: 您必须持有 AZW 才能使用变现功能',
                'page_member_main' => '1. 连接你的钱包\n2. 钱包里持有 '.env('AMOUNT_TOKEN_IS_VIP').'AZW\n3. 会员升级确认\n- VIP 3 - Vip 5\n- Super Vip 1 -> Super Vip 5 ( Coming Soon )',
                'page_member_help' => '第 1 步：连接您的钱包\n第 2 步: 您必须确保您已购买 AZW 代币\n第 3 步: 您可以访问网站 azworld.network 获取购买代币的说明\n第 4 步: 您同意钱包只看到余额\n第 5 步: 我们确认您已晋升为会员\n步骤 6: 您必须持有 AZW 才能使用变现功能',
                'page_home' => '👉 If you see Processing  - Our partners and system are checking it 1/ Pending —> 2/ Offchain —> 3/ Withdrawal —> 4/ Onchain ( To Wallet ). VIP1: '.env('AMOUNT_TOKEN_IS_VIP').'. VIP2: '.env('AMOUNT_TOKEN_IS_VIP1'),
                'is_show_banner' => 0,
                'is_show_video' => 1,
                'maintenance' => env('MAINTENANCE'),
                'min_withdraw' => (int)env('MIN_WITHDRAW') ?? 1000,
                'earn_status' => [
                    [   
                        'value' => 1,
                        'label' => '处理',
                        'color' => '#ffe58f'
                    ],
                    [   
                        'value' => 2,
                        'label' => '可用',
                        'color' => '#52c41a'
                    ],
                    [   
                        'value' => 3,
                        'label' => '取消',
                        'color' => '#df1010'
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
                'menu' => [
                    [
                        'title' => '付费点击 - 阅读以赚取',
                        'sub_title' => '- 您点击广告或阅读来赚取 - 您将赚取 $ \n- 每天有限点击 | 赚取 80% 的收入'
                    ],
                    [
                        'title' => 'Short Link To Earn',
                        'sub_title' => '- Shorten your long link ( documents...) and share\n- When user click you will get 80% Revenue of Ads'
                    ],
                    [
                        'title' => '购物现金返还',
                        'sub_title' => '- 从您喜爱的品牌的 2000 多家商店获得现金返还 \n- 购物时获取优惠券代码 + 最高 30% 的现金返还'
                    ],
                    [
                        'title' => '幸运轮 - 彩票',
                        'sub_title' => '- 只有持有者可以每天从广告基金中旋转 \n- 我们将使用 VRF Chainlink 更新彩票系统'
                    ],
                    [
                        'title' => '取款',
                        'sub_title' => '- 轻松提取您赚到的钱'
                    ],
                    [
                        'title' => '直播频道',
                        'sub_title' => '- 粉丝可以使用 $AZW 代币支持名人\n- 加入高级粉丝频道，享受独家福利'
                    ],
                    [
                        'title' => '推荐',
                        'sub_title' => '- 分享链接或代码，您将获得免费 AZW\n- 获得高达 20% 的购物返现佣金'
                    ],
                    [
                        'title' => 'AZ 商店 | 电子产品',
                        'sub_title' => '- 您可以以特价购买电子产品 - 合作伙伴折扣\n- 使用 $AZW - 只需轻扫，所有内容都会发送到电子邮件'
                    ],
                    [
                        'title' => '优惠 - 安装应用程序 - 调查',
                        'sub_title' => '- 点击这里获得更多奖励'
                    ],
                    [
                        'title' => '成员',
                        'sub_title' => ''
                    ],
                    'product_ids' => [
                        'subscription_silver',
                        'subscription_gold',
                        'donate_for_azworld'
                    ],
                    'withdraw_processing_time' => 'System checking',
                    'withdraw_fee' => 'Currently: Free - Burn 0%',
                ],
                
            ];
        }
        

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
