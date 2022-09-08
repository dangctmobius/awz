<?php

namespace App\Http\Controllers;

use App\Jobs\SendMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
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
        $orders = Order::where('status', 1)->where('user_id', $this->user->id)->with('product')->orderBy('id','desc')->skip($page*$limit )->take($limit)->get();
        $data = [];
        $data['count'] = Product::count();
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['items'] = $orders;
        return $this->responseOK($data);
        
        $data['items'] = $orders;
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
        //
        $rules = [
            'product_id'   => 'required',
        ];
        $messages = [
            'product_id.required'   => __('Product id require'),
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

        $data = [
            'product_id' => $request->product_id,
            'user_id' => $this->user->id,
            'qty' => '1',
            'status' => '1',
        ];

        $product = Product::where('id', $request->product_id)->first();

        $amount = (double)$product->price;

        $myBalance = (double)$this->user->balance;
        if($myBalance >= $amount) {
            $date = Order::create($data)->created_at;
            

            $my_user = User::where('id', $this->user->id)->decrement('balance', $amount);
            \DB::table('earns')->insert(['user_id' => $this->user->id, 'status' => 2, 'reward' => -($amount), 'subject' => 'shop', 'description' => 'Buy for product', 'created_at' => Carbon::now()]);

            $order['name'] = $product->name;
            $order['qty'] = 1;
            $order['price'] = $product->price;
            $order['image'] = $product->image_url;
            $order['date'] = $date;

            \Queue::push(new SendMail($this->user->email, $order));
            return $this->responseOK($data);
        } else {
            return $this->responseError("You don\'t have enough balance to buy this product", 200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
