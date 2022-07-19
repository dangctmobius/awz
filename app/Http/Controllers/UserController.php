<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Earn;
use App\Models\Followers;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth; //use this library
use Illuminate\Support\Carbon;

class UserController extends Controller
{


    protected $user;
    protected $input;
    protected $spin_list_item;
    public function __construct() {
        $this->middleware(['check_token','auth:api'])->except('address');
        $this->user = auth()->user();
        $this->input = [1,1,1,0,0,1,1,0,1,0,0,1,1,1,1,0,1,2,1,1,1,1,1,1,1,1,1,1,0,0,2,1,1,0,0,1,1,0,1,0,2,1,0,2,0,1,0,1,0,0,0,0,0,0,1,0,0,0,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,1,0,1,0,1,0,1,0,0,0,0,0,1,0,1,0];
        $this->spin_list_item = [
            
            ['color' => '#29a8ab', 'value' => 1, 'label' =>  '1'],
            ['color' => '#fed766', 'value' => 2, 'label' => '2'],
            ['color' => '#011f4b', 'value' => 5, 'label' =>  '5'],
            ['color' => '#03396c', 'value' => 10, 'label' =>  '10'],
            ['color' => '#851e3e', 'value' => 20, 'label' =>  '20'],
            ['color' => '#009688', 'value' => 30, 'label' =>  '30'],
            ['color' => '#3b5998', 'value' => 50, 'label' =>  '50'],
            ['color' => '#2ab7ca', 'value' => 100, 'label' => '100']
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPost(Request $request)
    {
        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $user_id = $this->user->id;
        $data = [];
        $data['total'] = Post::count();
        $products = Post::where('status', 1)->where('user_id', $user_id)->orderBy('id', 'desc')->withCount('comments', 'likes')->with('tags')
        ->with(['user' => function ($q) use($user_id) {
            $q->select('users.id', 'users.name', 'users.email', 'users.avatar')->with(['followers' => function ($q2) use($user_id) {
                $q2->select('users.id', 'users.name', 'users.email', 'users.avatar')->where('follower_id', $user_id);
            }]);
        }])
        ->with(['likes' => function ($q) use($user_id) {
            $q->where('likes.user_id', $user_id);
       }])
       ->skip($page*$limit )->take($limit)->get();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $products;
        return $this->responseOK($data);
    }

    public function info(Request $request)

    {   $user = User::where('id', $this->user->id)
        ->withCount('posts')
        ->withCount('following')
        ->withCount('followers')
       
        // ->with(['followers' => function ($q2) {
        //     $q2->select('users.id', 'users.name', 'users.email', 'users.avatar');
        // }])
        // ->with(['following' => function ($q2) {
        //     $q2->select('users.id', 'users.name', 'users.email', 'users.avatar');
        // }])
        ->first();
        return $this->responseOk($user);
    }


    public function other_info($id, Request $request)

    {   
        $user_id = $this->user->id;
        
        $user = User::where('id', $id)
        ->withCount('posts')
        ->withCount('following')
        ->withCount('followers')
        ->with(['followers' => function ($q2) use($user_id) {
            $q2->select('users.id', 'users.name', 'users.email', 'users.avatar')->where('follower_id', $user_id);
        }])
        // ->with(['following' => function ($q2) {
        //     $q2->select('users.id', 'users.name', 'users.email', 'users.avatar');
        // }])
        ->first();
        return $this->responseOk($user);
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
    public function update(Request $request)
    {
        $name = $request->name;
        $phone = $request->phone;
        $now = \Carbon\Carbon::now();
        $now_format = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $rand = rand(1000, 9999);
        $user_id = $this->user->id;
        if(($request->file("image"))!=null)
        {
            $photo = $request->file("image");
            $ext = $photo->getClientOriginalExtension();
            $fileName = $now->year.$now->month.$now->day.'_'.$user_id.'_'.$rand . '.' .$ext;
            $thumbSm = 'thumb_sm_' . $rand . '.' .$ext;
            $image = Image::make($photo->getRealPath());
            \Storage::disk('s3')->put('images/products'.'/'.$fileName,$image->encode(),'public');

        }
        $data = [
        'name' => $name ?? 'None',
        'telegram' => $phone ?? 'None'
        ];

        if(isset($fileName))  {
            $data['avatar'] = env('AWS_URL').'images/products/'.$fileName;
         }else{
            
         }
        $update = User::where('id', $user_id)->update($data);
        $user = User::where('id', $user_id)->first();
        $data = [];
        $data['item'] = $user;
        if($user) {
            return $this->responseOK($data, 'success');
        } else {
            return $this->responseError();
        }
    }

    public function address(Request $request)
    {   
        // try {
        //     $token = JWTAuth::getToken();
        //     $apy = JWTAuth::getPayload($token)->toArray();
        // } catch(\Exception $e){
        //     echo json_encode(['error' => 'code 22']);
        //     echo '<script>history.back();</script>';
        //     die;
        // }
        // $time_request = $request->time_request;
        // $code = $request->code;
        // if(isset($time_request) && md5(md5(env('SECURITY_CODE') . env('APP_VERSION') .$time_request) == $code))
        // {   $token = \DB::table('token_requests')->where('token', $code)->count();
        //     // if (!$token > 0) {
        //     //     echo json_encode(['error' => 'code 20']);
        //     //     echo '<script>history.back();</script>';
        //     //     die;
        //     // }
        //     // \DB::table('token_requests')->insert(['token' => $code, 'timestamp' => $time_request, 'created_at' => time(), 'ip' => $this->getIp()]);
        // } else {
        //     echo json_encode(['error' => 'code 21']);
        //     echo '<script>history.back();</script>';
        //     die;
        // }

        // $email = $request->email;
        $address = $request->address;
        $data = [
            'address' => $address ?? NULL,
            ];

        if ($this->user->metamask_address && $this->user->metamask_address == $address) {
            
            $update = User::where('id', $this->user->id)->update($data);
            if($update) {
                return $this->responseOK('Update wallet address success');
            } else {
                return $this->responseError('Update wallet address error', 200);
            }
            

        }
        if($this->user->metamask_address && $this->user->metamask_address != $address) {

            return $this->responseError('You connected this email with other address', 200);

        }

        $check_address = User::where('address', $address)->orWhere('metamask_address', $address)->first();

        if($check_address)
        {
            return $this->responseError('Can not connect this address, please try other address', 200);
            
        } else {
            $update = User::where('id', $this->user->id)->update($data);
            if($update) {
                return $this->responseOK('Update wallet address success');
            } else {
                return $this->responseError('Update wallet address error', 200);
            }
        }
        
    
            
        
    }

    public function refs(Request $request)
    {   
        $user_id = $this->user->id;
        $user = User::where('id', $user_id)->with(['refs' => function ($q) use($user_id) {
            $q->where('id', '<>' ,$user_id);
    }])->first();
        
        if($user) {
            $data = $user->refs;
            return $this->responseOK($data, 'success');
        } else {
            return $this->responseError();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function get_balance()
    {   
        $address = $this->user->address;
        if($address)
        {
            $response = $this->bscCheckBalance($address);
            if ($response && $response['message'] == 'OK') {
                $data = $response['result'] / 10 ** env('CONTRACT_DEC');
                return $this->responseOK($data, 'success');
            }
        } else {
            return $this->responseOK('You have not connected the wallet. Please connect your address!', 200);
        }
        
    }

    public function controller_check_vip()
    {   
        $address = $this->user->address;
        if($address)
        {   
            $balance = $this->check_vip($address);
            // $balance = 0;
            if((int)$balance > 0)
            {
                if ($balance >= (int)env('AMOUNT_TOKEN_IS_VIP_3'))
                {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'VIP 4'], 'success');
                } else if ($balance >= (int)env('AMOUNT_TOKEN_IS_VIP2')) {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'VIP 3'], 'success');
                } else if ($balance >= (int)env('AMOUNT_TOKEN_IS_VIP1')) {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'VIP 2'], 'success');
                } else if ($balance >= (int)env('AMOUNT_TOKEN_IS_VIP')) {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'VIP 1'], 'success');
                } else {
                    return $this->responseOK(['is_vip' => 0, 'vip_label' => 'FREE'], 'success');
                }
                
            } else {
                return $this->responseOK(['is_vip' => 0], 'success');
            }
        } else {
            return $this->responseError('You have not connected the metamask wallet. Please connect your address!', 200);
        }
        
    }

    public function disconnect() {

        $id = $this->user->id;
        $update = User::where('id', $id)->update(['address'=> NULL, 'metamask_address' => $this->user->metamask_address ? $this->user->metamask_address : $this->user->address]);
        $user = User::where('id', $id)->first();
        $data = [];
        $data['item'] = $user;
        if($update) {
            return $this->responseOK($data, 'success');
        } else {
            return $this->responseError();
        }
    }

    public function total_spin() 
    {

        $user_id = $this->user->id;
        $address = $this->user->address;
        // if($address && $this->check_vip($address))
        // {   
            $after = 48;
            // echo (Carbon::now()->toDateTimeString());
            $date = Carbon::now()->subHours($after)->toDateTimeString();
            // echo ($date);
            // dd(Carbon::parse($date)->diffInHours(Carbon::now()));
            $total_earn = Earn::where('user_id', $user_id)->where('subject', 'spin')->where('created_at' , '>=', $date)->count();
            if($total_earn < (int)env('LIMIT_REWARD_SPIN')) {
                    $spin = (int)env('LIMIT_REWARD_SPIN') - $total_earn;
                    $earn =  Earn::where('subject', 'spin')->where('status', 2)->sum('reward');
                    $data['total_spin'] = $spin;
                    $data['spin_pool'] = (int)env('POOL');
                    if($earn) {
                        $data['remain_pool'] = $data['spin_pool'] - $earn;
                    }
                    return $this->responseOK($data, 'success');
            } else {

                $earn = Earn::where('user_id', $user_id)->where('subject', 'spin')->where('created_at' , '>=', $date)->orderBy('id', 'desc')->first();

                $created_at = ($earn->created_at->toDateTimeString());

                $count_down = $after * 60 -  Carbon::parse($created_at)->diffInMinutes(Carbon::now());

                $count_down = floor($count_down / 60).'h'.($count_down -   floor($count_down / 60) * 60);

                return $this->responseError('You spin max every 36 hours. Spin after '.$count_down . 'm', 200);
            }
           
        // } else {
        //     return $this->responseError('You\'re not a VIP member.', 200);
        // }
    }


    public function spin_pool() 
    {

        $user_id = $this->user->id;
        $address = $this->user->address;
        $earn =  Earn::where('subject', 'spin')->where('status', 2)->sum('reward');
        $data['spin_pool'] = env('POOL');
        if($earn) {
            $data['remain_pool'] = ($data['spin_pool'] - $earn) < 0 ? 0 : ($data['spin_pool'] - $earn) ;
        } else {
            $data['remain_pool'] = $data['spin_pool'];
        }
        
        return $this->responseOK($data, 'success');
    }


    public function spin() 
    {

        $user_id = $this->user->id;
        $address = $this->user->address;
        $balance = $this->check_vip($address);
        // if(!$this->user->is_vip){
        //     return $this->responseError('You are not in Mainnet List', 200);
        // }
        if( $balance >= (int)env('AMOUNT_TOKEN_IS_VIP1'))
        {   
            $rand_keys = array_rand($this->input, 1);
            
            return $this->responseOK($this->input[$rand_keys], 'success');
           
        } else {
            return $this->responseError('You\'re not a VIP 2 member.', 200);
        }
    }

    public function list_spin() 
    {

        $user_id = $this->user->id;
        $address = $this->user->address;
        // if($address && $this->check_vip($address))
        // {   
            
            $data['items'] = $this->spin_list_item;
            return $this->responseOK($data, 'success');
           
        // } else {
        //     return $this->responseError('You\'re not a VIP member.', 200);
        // }
    }

    public function earn_spin(Request $request) 
    {
        

        $user_id = $this->user->id;
        $address = $this->user->address;
        $spin_code = $request->spin_code;
        $balance = $this->check_vip($address);
        // if(!$this->user->is_vip){
        //     return $this->responseError('You are not in Mainnet List', 200);
        // }
        $now = Carbon::now();

        $after = 36;
        // echo (Carbon::now()->toDateTimeString());
        $date = $now->subHours($after)->toDateTimeString();
        // echo ($date);
        // dd(Carbon::parse($date)->diffInHours(Carbon::now()));

        if($address && $balance)
        {   

            if( $balance >= (int)env('AMOUNT_TOKEN_IS_VIP1')) {

                $total_earn = Earn::where('user_id', $user_id)->where('subject', 'spin')->where('created_at' , '>=', $date)->count();
                if($total_earn < (int)env('LIMIT_REWARD_SPIN')) {
                        $reward = 1;
                        foreach($this->input as $item) {
                            if(md5($item.env('SECURITY_CODE')) == $spin_code) {
                                $reward = $item;
                                break;
                            }
                        }
                        $earn =  Earn::where('subject', 'spin')->where('status', 2)->sum('reward');
                        $pool = env('POOL');
                        if((($pool - $earn) - $this->spin_list_item[$reward]['value'])  <= 0 ) {
                            return $this->responseError('Max pool reward.', 200);
                        }


                        $price = $this->getPrice();
                        $reward =  $this->spin_list_item[$reward]['value'] / $price;
                        $reward = intval($reward);

                        $key = $user_id.'_'.$now;
                        $history = \DB::table('earns')->insert(['user_id' => $user_id, 'status' => 1, 'reward' => $reward, 'subject' => 'spin', 'description' => 'Reward from spin', 'created_at' => Carbon::now(), 'key' => $key]);
                        User::where('id', $user_id)->increment('pending_balance',  $reward);
                        return $this->responseOK(1, 'success');
                } else {

                    $earn = Earn::where('user_id', $user_id)->where('subject', 'spin')->where('created_at' , '>=', $date)->orderBy('id', 'desc')->first();

                    $created_at = ($earn->created_at->toDateTimeString());

                    $count_down = $after * 60 -  Carbon::parse($created_at)->diffInMinutes(Carbon::now());

                    $count_down = floor($count_down / 60).'h'.($count_down -   floor($count_down / 60) * 60);

                    return $this->responseError('You spin max every 36 hours. Spin after '.$count_down . 'm', 200);
                }
            } else {
                return $this->responseError('You\'re not a VIP 2 member.', 200);    
            }
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
    }


    


    
    public function destroy($id)
    {
        //
    }



    public function follow($id, Request $request) 
    {

        $user_id = $this->user->id;
        $myInfo = auth()->user();

        $check_followed = Followers::where('follower_id', $myInfo->id)->where('following_id', $id)->first();
        if( ! $check_followed) {
            $myInfo->following()->attach($id);
        } 

        return $this->responseOK("Follow success", 'success');

    }


    public function unfollow($id, Request $request) 
    {

        $user_id = $this->user->id;
        $myInfo = auth()->user();

        $check_followed = Followers::where('follower_id', $myInfo->id)->where('following_id', $id)->first();
        if($check_followed) {
            $myInfo->following()->detach($id);
        } 

        return $this->responseOK("Unfollow success", 'success');

    }


    public function followers(Request $request) 
    {

        $user_id = $this->user->id;
        $myInfo = User::select('id', 'name', 'email', 'avatar')->where('id', $this->user->id)->with(['followers' => function ($q2) use($user_id) {
            $q2->select('users.id', 'users.name', 'users.email', 'users.avatar');
        }])->first();
        if($myInfo) {
            $data = $myInfo;
        }  else {
            $data = [];
        }

        return $this->responseOK($data, 'success');

    }

    public function following(Request $request) 
    {

        $user_id = $this->user->id;
        $myInfo = User::select('id', 'name', 'email', 'avatar')->where('id', $this->user->id)->with(['following' => function ($q2) use($user_id) {
            $q2->select('users.id', 'users.name', 'users.email', 'users.avatar');
        }])->first();
        if($myInfo) {
            $data= $myInfo;
        }  else {
            $data= [];
        }

        return $this->responseOK($data, 'success');

    }


    public function donate(Request $request) 
    {

        $user_id = $this->user->id;
        $amount = (double)$request->amount;
        $post_id = $request->post_id;
        $influ_id = Post::where('id', $post_id)->first();

        $myBalance = (double)$this->user->balance;
        if($myBalance >= $amount) {
            
            $influ = User::where('id', $influ_id->user_id)->increment('balance', $amount);
            $my_user = User::where('id', $user_id)->decrement('balance', $amount);
            \DB::table('earns')->insert(['user_id' => $user_id, 'status' => 2, 'reward' => -($amount), 'subject' => 'donate', 'description' => 'Donate for post', 'created_at' => Carbon::now()]);
            \DB::table('earns')->insert(['user_id' => $influ_id->user_id, 'status' => 2, 'reward' => ($amount), 'subject' => 'donate', 'description' => 'Donate for post', 'created_at' => Carbon::now()]);
            \DB::table('donates')->insert(['user_id' => $user_id, 'post_id' => $post_id, 'amount' => $amount]);

        }  else {
            return $this->responseError("You don\'t have enough balance to donate", 200);
        }
        $my_user = User::where('id', $user_id)->first();
        return $this->responseOK($my_user, 'success');

    }

    public function ticket(Request $request)
    {
        $subject = $request->subject;
        $description = $request->description;
        $user_id = $this->user->id;

        $total24h = \DB::table('tickets')->where('user_id', $user_id)->whereDate('created_at', Carbon::today())->count();
        if($total24h > 0) {
            return $this->responseError(' We received your message, only 24h for 1 ticket. We will reply you within 24h', 200);
        }
        $insert = \DB::table('tickets')->insert(
            [
                'subject' => $subject,
                'description' => $description,
                'user_id' => $user_id,
                'created_at' => Carbon::now()
            ]
        );
        if($insert) {
            return $this->responseOK('ok', 'success');
        }

        
    }

}
