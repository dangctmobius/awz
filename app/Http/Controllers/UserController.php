<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Earn;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth; //use this library
use Illuminate\Support\Carbon;

class UserController extends Controller
{


    protected $user;
    protected $input;
    public function __construct() {
        $this->middleware(['check_token','auth:api'])->except('address');
        $this->user = auth()->user();
        $this->input = array(1,1,2,3,2,1,3,2,3,5,6,2,3,5,4,2,5,6,8,2,3,7,6,4,5,3,2,1,3,3,7,8,9,3,2,1,5,3);
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
        $products = Post::where('status', 1)->where('user_id', $user_id)->orderBy('id', 'desc')->withCount('comments', 'likes')->with('user') ->with(['likes' => function ($q) use($user_id) {
            $q->where('likes.user_id', $user_id);
    }])->skip($page*$limit )->take($limit)->get();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $products;
        return $this->responseOK($data);
    }

    public function info(Request $request)

    {   $user = User::where('id', $this->user->id)->withCount('posts')->first();
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
        try {
            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();
        } catch(\Exception $e){
            dd($e);
            echo json_encode(['error' => 'code 22']);
            echo '<script>history.back();</script>';
            die;
        }
        $time_request = $request->time_request;
        $code = $request->code;
        if(isset($time_request) && md5(md5(env('SECURITY_CODE') . env('APP_VERSION') .$time_request) == $code))
        {   $token = \DB::table('token_requests')->where('token', $code)->count();
            // if (!$token > 0) {
            //     echo json_encode(['error' => 'code 20']);
            //     echo '<script>history.back();</script>';
            //     die;
            // }
            // \DB::table('token_requests')->insert(['token' => $code, 'timestamp' => $time_request, 'created_at' => time(), 'ip' => $this->getIp()]);
        } else {
            echo json_encode(['error' => 'code 21']);
            echo '<script>history.back();</script>';
            die;
        }

        $email = $request->email;
        $address = $request->address;
        
        $data = [
        'address' => $address ?? '',
        ];
        $update = User::where('email', $email)->update($data);
        // $user = User::where('email', $email)->first();
        // $data = [];
        // $data['item'] = $user;
        if($update) {
            return $this->responseOK(null, 'success');
            // return \Redirect::to('https://connect.azworld.network?connect=success');
        } else {
            return $this->responseError();
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
                $data = $response['result'];
                return $this->responseOK($data, 'success');
            }
        } else {
            return $this->responseError('You have not connected the metamask wallet. Please connect your address!', 200);
        }
        
    }

    public function controller_check_vip()
    {   
        $address = $this->user->address;
        if($address)
        {
            if($balance = $this->check_vip($address))
            {
                if ($balance > (int)env('AMOUNT_TOKEN_IS_SILVER'))
                {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'SILVER'], 'success');
                } else if ($balance > (int)env('AMOUNT_TOKEN_IS_GOLD')) {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'GOLD'], 'success');
                }else if ($balance > (int)env('AMOUNT_TOKEN_IS_PLATINUM')) {
                    return $this->responseOK(['is_vip' => 1, 'vip_label' => 'PLATINUM'], 'success');
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
                    return $this->responseOK($spin, 'success');
            } else {
                return $this->responseError('You spin max daily.', 200);
            }
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
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
        if($address && $this->check_vip($address))
        {   
            
            $data = [
                '1 point',
                '2 point',
                '3 point',
                '4 point',
                '5 point',
                '6 point',
                '7 point',
                '8 point',
                '9 point',
                '10 point',
            ];
            return $this->responseOK($data, 'success');
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
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
                    $history = \DB::table('earns')->insert(['user_id' => $user_id, 'status' => 2, 'reward' => $reward + 1, 'subject' => 'spin', 'description' => 'Reward token from spin', 'created_at' => Carbon::now()]);
                    User::where('id', $user_id)->increment('balance', $reward);
                    return $this->responseOK(null, 'success');
                    return $this->responseOK($spin, 'success');
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
}
