<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferController;
use App\Models\User;
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


Route::get('/reset_task', function(){
    \DB::table('user_ptc_task')->truncate();
});

Route::get('/reset_token', function(){
    \DB::table('token_requests')->truncate();
});


Route::get('/payments/offers/tapjoy', [OfferController::class, 'offer_tapjoy']);



Route::get('/send_fcm', function () {

    $response = Http::withHeaders([
        'Authorization' => env('FCM_ADMIN_TOKEN'),
        'Content-Type' => 'application/json' 
    ])->post('https://fcm.googleapis.com/fcm/send', [
        // 'to' => '/topics/all',
        'registration_ids' => ['dYB_ZOXgS0N1ilGDezU4LR:APA91bF1tbWWL_SBxw1WX1YYqIne1HjSpno1IYZohYeORb8-oZM3iED-zOZmebE51rLViOcvB8PsYpfUgNH9ynR0-APXaMpvo4KV3JH2S_WCjtJKJE7zqAIg2KzkVetGyO9odW1msq0h'],
        'notification'=>[
            "title"=> "AZ World SocialFi",
            "body"=> "New version updated on chplay",
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

