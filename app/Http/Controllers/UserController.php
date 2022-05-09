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
        $this->input = array(1,1,1,0,0,1,2,2,1,0,0,2,1,1,4,2,1,2,1,2,3,1,6,4,1,3,2,1,3,3,7,1,1,3,2,1,1,1,1,0,7,1,0,2,0,1,5);
        $this->spin_list_item = [
            
            ['color' => '#29a8ab', 'value' => 2, 'label' =>  '2'],
            ['color' => '#fed766', 'value' => 5, 'label' => '5'],
            ['color' => '#011f4b', 'value' => 10, 'label' =>  '10'],
            ['color' => '#03396c', 'value' => 15, 'label' =>  '15'],
            ['color' => '#851e3e', 'value' => 20, 'label' =>  '20'],
            ['color' => '#009688', 'value' => 25, 'label' =>  '25'],
            ['color' => '#3b5998', 'value' => 30, 'label' =>  '30'],
            ['color' => '#2ab7ca', 'value' => 50, 'label' => '50']
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
        'name' => $name ?? 'Không tên',
        'phone' => $phone ?? 'Không tên'
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
        'address' => $address ?? '',
        ];

        if ($this->user->address || count($this->user->address) <= 10) {
            return $this->responseError('You are connected address '.$this->user->address, 200);
        }

        $update = User::where('id', $this->user->id)->update($data);
        // $user = User::where('email', $email)->first();
        // $data = [];
        // $data['item'] = $user;
        if($update) {
            return $this->responseOK('Update wallet address success');
            // return \Redirect::to('https://connect.azworld.network?connect=success');
        } else {
            return $this->responseError('Update wallet address error', 200);
            // return \Redirect::to('https://connect.azworld.network?connect=error');
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
            return $this->responseOK('You have not connected the metamask wallet. Please connect your address!', 200);
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
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'VIP 3'], 'success');
                } else if ($balance >= (int)env('AMOUNT_TOKEN_IS_VIP2')) {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'VIP 2'], 'success');
                } else if ($balance >= (int)env('AMOUNT_TOKEN_IS_VIP1')) {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'VIP 1'], 'success');
                } else if ($balance >= (int)env('AMOUNT_TOKEN_IS_VIP')) {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'VIP'], 'success');
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
        $update = User::where('id', $id)->update(['address'=> NULL]);
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
        if($address && $this->check_vip($address))
        {   
            $total_earn = Earn::where('user_id', $user_id)->where('subject', 'spin')->whereDate('created_at', Carbon::today())->count();
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
                return $this->responseError('You spin max daily.', 200);
            }
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
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
        if($address && $this->check_vip($address))
        {   
            $rand_keys = array_rand($this->input, 1);
            
            return $this->responseOK($this->input[$rand_keys], 'success');
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
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
        if($address && $this->check_vip($address))
        {   
            $total_earn = Earn::where('user_id', $user_id)->where('subject', 'spin')->whereDate('created_at', Carbon::today())->count();
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
                    $history = \DB::table('earns')->insert(['user_id' => $user_id, 'status' => 2, 'reward' => $this->spin_list_item[$reward]['value'], 'subject' => 'spin', 'description' => 'Reward from spin', 'created_at' => Carbon::now()]);
                    User::where('id', $user_id)->increment('balance',  $this->spin_list_item[$reward]['value']);
                    return $this->responseOK(1, 'success');
            } else {
                return $this->responseError('You spin max daily.', 200);
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

        }  else {
            return $this->responseError("You don\'t have enough balance to donate", 200);
        }
        $my_user = User::where('id', $user_id)->first();
        return $this->responseOK($my_user, 'success');

    }

}
