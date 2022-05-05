<?php

namespace App\Http\Controllers;

use App\Jobs\SendMail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

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
    public function index()
    {
        //
        $orders = Order::where('user_id', $this->user->id)->with('product')->get();
        return $this->responseOK($orders);
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
        $date = Order::create($data)->created_at;

        $product = Product::where('id', $request->product_id)->first();

        $order['name'] = $product->name;
        $order['qty'] = 1;
        $order['price'] = $product->price;
        $order['image'] = $product->image_url;
        $order['date'] = $date;

        \Queue::push(new SendMail($this->user->email, $order));
        return $this->responseOK($data);
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
