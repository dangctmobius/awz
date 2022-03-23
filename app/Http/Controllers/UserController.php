<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth; //use this library

class UserController extends Controller
{


    protected $user;
    public function __construct() {
        $this->middleware(['check_token','auth:api'])->except('address');
        $this->user = auth()->user();
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
            echo json_encode(['error' => 'code 22']);
            // echo '<script>history.back();</script>';
            die;
        }
        $time_request = $request->time_request;
        $code = $request->code;
        if(isset($time_request) && md5(md5(env('SECURITY_CODE') . env('APP_VERSION') .$time_request) == $code))
        {   $token = \DB::table('token_requests')->where('token', $code)->count();
            if (!$token > 0) {
                echo json_encode(['error' => 'code 20']);
                // echo '<script>history.back();</script>';
                die;
            }
            \DB::table('token_requests')->insert(['token' => $code, 'timestamp' => $time_request, 'created_at' => time(), 'ip' => $this->getIp()]);
        } else {
            echo json_encode(['error' => 'code 21']);
            // echo '<script>history.back();</script>';
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
    public function destroy($id)
    {
        //
    }
}
