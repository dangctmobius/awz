<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\VerifyEmailCodeController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('login', function () {
    return response()->json(['error'=> 'Not Authencation']);
})->name('login');

// Route::get('/verify/{email}', [VerifyEmailCodeController::class, 'verify']);

// Route::get('/demo', function(){
//     return view(('mail_send'));
// });

Route::get('/tracking', function () {
    return  redirect('https://amazon.com');;
});

Route::get('testnet', function () {
    $users = User::select('address', 'balance')->whereNotNull('address')->where('address', '<>', '')->orderBy('balance', 'desc')->get();
    $html =  '<table class="table mb-0"><thead class="thead-light"><tr><th>Wallet</th><th>Pointoi</th></tr></thead><tbody id="#Data">';
    foreach($users as $user) {
      
        $html .= '<tr><th>'. $user->address .'</th> <th>'. $user->balance .'</th> </tr>';
    }

    $html .= '</table>';
    echo $html;
})->name('testnet');

Route::get('/azw_price', [Controller::class, 'getPrice']);

Route::get('/reset_task', function(){
    \DB::table('user_ptc_task')->truncate();
});

Route::get('/reset_token', function(){
    \DB::table('token_requests')->truncate();
});


Route::get('/payments/offers/tapjoy', [OfferController::class, 'offer_tapjoy']);



Route::get('/send_fcm', function () {

    $url = 'https://fcm.googleapis.com/fcm/send';

    $response = Http::withHeaders([
        'Authorization' => env('FCM_ADMIN_TOKEN'),
        'Content-Type' => 'application/json' 
    ])->post($url, [
        'registration_ids' => ['id'],
        'notification'=>[
            "title"=> "AZ World SocialFi",
            "body"=> "Refund your purchase. Reason: Must have old account of Spotify Before",
            "sound" => "default"
        ],
        "data"=>[
            "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
            "priority"=> "high",
            "collapse_key"=> "type_a",
            "data"=> [
                "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                "other_data"=> "any message",
                "title"=> "AZ World SocialFi",
                "body"=> "",
                "priority"=> "high",
                "click_action"=> "FLUTTER_NOTIFICATION_CLICK"
                ]
            ],
    ]);
    return $response;
    
});


Route::get('/sync_fhr', function () {

    $products = \DB::table('products')->get();
    $url = 'https://pol.f88.vn/pol/api/affilate/add_new/array';
    $client = new Client();
    foreach($products as $product) {
        $data = [
            "name" => $product->name,
            "phone" => $product->phone,
            "select1" => $product->select1,
            "link" => $product->link,
            "TransactionID" => $product->transaction_id,
            "ReferenceType" => $product->reference_type,
            "ReferenceID" => $product->reference_id,
            "CurrentGroupID" => $product->current_group_id,
            "Source" => $product->source,
            "Campaign" => $product->campaign,
            "str_source_group" => $product->str_source_group,
            "str_secondary_source" => $product->str_secondary_source,
            "isDigital" => $product->isdigital,
        ];
        
        // $res = $client->request('POST', $url, [
        //     'form_params' => [
        //         'data' => $data
        //     ]
        // ]);
     
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate, br',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36',
        ])->post($url , ["data" => [$data]]);
        var_dump($response);
    }
    return $response;
});

