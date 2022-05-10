<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
class CommentController extends Controller
{   

    private $user;
    public function __construct()
    {
        $this->user = auth()->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    public function get_comment_by_post_id(Request $request, $post_id)
    {

        $page = $request->page ? (int)$request->page : 0;
        $limit = $request->limit ? (int)$request->limit : 20;
        $user_id = $this->user->id;

        $comments = Comment::where('post_id', $post_id)->with('user')->orderBy('id','desc')->skip($page*$limit )->take($limit)->get();
        
        $data = [];
        $data['count'] = Comment::count();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $comments;
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
            'body'   => 'required',
        ];
        $messages = [
            'body.required'   => __('Yêu cầu điền nội dung bình luận'),
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

        $now = \Carbon\Carbon::now();
        $now_format = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $rand = rand(1000, 9999);
        $user_id = $this->user->id;
        $body = $request->body;
        $blacklist = explode("|", env('BLACK_LIST'));
        $banned = $this->striposa($body, $blacklist);
        if($banned !== false){
            return $this->responseError('Bad word');
        }

        $data = [
        'body' => $body ?? 'Comment',
        'user_id' => $user_id ?? 1,
        'parent_id' => $request->parent_id,
        'post_id' => $request->post_id,
        'created_at' => time()
        ];

        $insertId = Comment::insertGetId($data);
        $comment = Comment::where('id', $insertId)->first();
        $data = [];
        $data['item'] = $comment;
        if($comment) {
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

    public function striposarray($haystack, $needles=array(), $offset=0) {
        $chr = array();
        foreach($needles as $needle) {
                $res = stripos($haystack, $needle, $offset);
                if ($res !== false) $chr[$needle] = $res;
        }
        if(empty($chr)) return false;
        return min($chr);
    }
}
