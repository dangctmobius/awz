<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Post;
use App\Models\Like;
use App\Models\Tag;
use App\Models\PostTagRelationship;

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
        $list_my_user_following = $this->user->following()->get();

        $data['total'] = Post::whereIn('user_id', $list_my_user_following)->count();
        $products = Post::where('status', 1)->orderBy('id', 'desc')
        // ->whereIn('user_id', $list_my_user_following)
        ->withCount('comments', 'likes')
        ->with('tags')
        ->with(['user' => function ($q) use($user_id) {
            $q->select('users.id', 'users.name', 'users.email', 'users.avatar',     'users.verify')->with(['followers' => function ($q2) use($user_id) {
                $q2->select('users.id', 'users.name', 'users.email', 'users.avatar', 'users.verify')->where('follower_id', $user_id);
            }]);
        }])
       
        // ->with('user.followers')
        ->with(['likes' => function ($q) use($user_id) {
             $q->where('likes.user_id', $user_id);
        }])
        ->skip($page*$limit )->take($limit)->get();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $products;
        return $this->responseOK($data);
    }

    public function list_by_tag(Request $request)
    {
        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $user_id = $this->user->id;
        $tag = $request->tag;
        $data = [];
        $list_my_user_following = $this->user->following()->get();

        $data['total'] = Post::whereIn('user_id', $list_my_user_following)->count();
        $products = Post::where('status', 1)->orderBy('id', 'desc')
        // ->whereIn('user_id', $list_my_user_following)
        ->withCount('comments', 'likes')
        // ->with(['tags' => function ($q_t) use($tag) {
        //     $q_t->where('tag', $tag);
        // }])
        ->with('tags')
        ->whereHas('tags', function ($query) use($tag) {
            return $query->where('tag', $tag);
        })
        ->with(['user' => function ($q) use($user_id) {
            $q->select('users.id', 'users.name', 'users.email', 'users.avatar',     'users.verify')->with(['followers' => function ($q2) use($user_id) {
                $q2->select('users.id', 'users.name', 'users.email', 'users.avatar', 'users.verify')->where('follower_id', $user_id);
            }]);
        }])
       
        // ->with('user.followers')
        ->with(['likes' => function ($q) use($user_id) {
             $q->where('likes.user_id', $user_id);
        }])
        ->skip($page*$limit )->take($limit)->get();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $products;
        return $this->responseOK($data);
    }


    public function get_list_by_user($id, Request $request)
    {
        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $user_id = $this->user->id;
        $data = [];

        $data['total'] = Post::count();
        $products = Post::where('status', 1)->orderBy('id', 'desc')
        ->where('user_id', $id)
        ->withCount('comments', 'likes')
        ->with('tags')
        ->with(['user' => function ($q) use($user_id) {
            $q->select('users.id', 'users.name', 'users.email', 'users.avatar',     'users.verify')->with(['followers' => function ($q2) use($user_id) {
                $q2->select('users.id', 'users.name', 'users.email', 'users.avatar', 'users.verify')->where('follower_id', $user_id);
            }]);
        }])
       
        // ->with('user.followers')
        ->with(['likes' => function ($q) use($user_id) {
             $q->where('likes.user_id', $user_id);
        }])
        ->skip($page*$limit )->take($limit)->get();
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
            'content.required'   => __('Content require'),
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
        $tags = $request->tags;

        $tags = explode(" ",$tags);
        $insert_tags = [];
        foreach($tags as $tag) {
            if (str_starts_with($tag, '#')) {
                array_push($insert_tags,$tag);
            }
        }

        
        $post = New Post;

        $data = [
        'content' => $content ?? 'None',
        'user_id' => $user_id ?? 1,
        'status'    => 1,
        'created_at' => time()
        ];

        $post->content = $content ?? 'No content';
        $post->user_id = $user_id;
        $post->status = 1;
        // $post->created_at = time();

        if(isset($fileName))  {
            $post->image = env('AWS_URL').'images/products/'.$fileName;
         }else{
            $post->image = '';
         }

        $post->save();
        // $insertId = Post::insertGetId($data);
        // $post = Post::where('id', $insertId)->first();


        foreach($insert_tags as $t) {
            $result = Tag::where("tag", $t)->first();

          $ptr = new PostTagRelationship;
          $ptr->post_id = $post->id;
          $ptr->user_id = $user_id;

          if ($result) {
              // tag found just bind it
              $ptr->tag_id = $result->id;
              $post->tags()->attach($ptr->tag_id);
          } else {
              $tag = new Tag;
              $tag->tag = $t;
              $tag->save();
              $ptr->tag_id = $tag->id;
              $post->tags()->attach($ptr->tag_id);
          }
        }

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
    public function like($id)
    {   
        $like = Like::insert(['post_id' => $id, 'user_id' => $this->user->id]);
        if($like){
            return $this->responseOK(null, 'success');
         }else{
            return $this->responseError();
         }
    }

    public function unlike($id)
    {   
        $like = Like::where('post_id', $id)->where('user_id', $this->user->id)->delete();
        if($like){
            return $this->responseOK(null, 'success');
         }else{
            return $this->responseError();
         }
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
        $post = Post::where('id', $id)->where('user_id', $this->user->id)->delete();
        if($post){
            return $this->responseOK(null, 'success');
         }else{
            return $this->responseError();
         }

    }
}
