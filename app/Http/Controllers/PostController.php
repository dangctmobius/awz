<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Post;
class PostController extends Controller
{   

    private $user;
    public function __construct()
    {   
        $this->middleware(['check_token']);
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $user_id = $this->user->id;
        $data = [];
        $data['total'] = Post::count();
        $products = Post::where('status', 1)->orderBy('id', 'desc')->withCount('comments')->with('user')->skip($page*$limit )->take($limit)->get();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $products;
        return $this->responseOK($data);
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
        $rules = [
            'content'   => 'required',
        ];
        $messages = [
            'content.required'   => __('Yêu cầu điền nội dung bài viết'),
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

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
        $content = $request->content;
        $data = [
        'content' => $content ?? 'Không tên',
        'user_id' => $user_id ?? 1,
        'likes' => 0,
        'status'    => 1,
        'created_at' => time()
        ];

        if(isset($fileName))  {
            $data['image'] = env('AWS_URL').'images/products/'.$fileName;
         }else{
            $data['image']= env('AWS_URL').'storage/images/products/460325024.png';
         }
        $insertId = Post::insertGetId($data);
        $post = Post::where('id', $insertId)->first();
        $data = [];
        $data['item'] = $post;
        if($post) {
            return $this->responseOK($data, 'success');
        } else {
            return $this->responseError();
        }
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function like($id, Request $request)
    {   
        // $is_like = $request->is_like;

        // $update = Post::where('id', $id)->update(['likes' => $is_like, 'updated_at'=>time()]);
        // if($update){
        //     return $this->responseOK(null, 'success');
        //  }else{
        //     return $this->responseError();
        //  }
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
