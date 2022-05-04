<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\EarnController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\CashbackController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AirdropController;
use App\Http\Controllers\AirdropSubmitController;
use App\Http\Controllers\ProductController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify', [AuthController::class, 'verify']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/changepass', [AuthController::class, 'changepass']);    
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'post'

], function ($router) {
    Route::get('/list', [PostController::class, 'index'])->middleware(['check_token','auth:api']);   
    Route::get('/list_by_tag', [PostController::class, 'list_by_tag'])->middleware(['check_token','auth:api']);  
    Route::post('/add', [PostController::class, 'store'])->middleware(['check_token','auth:api']);
    Route::post('/like/{id}', [PostController::class, 'like'])->middleware(['check_token','auth:api']);   
    Route::post('/unlike/{id}', [PostController::class, 'unlike'])->middleware(['check_token','auth:api']);   
    Route::post('/delete/{id}', [PostController::class, 'destroy'])->middleware(['check_token','auth:api']);  
    
    
    Route::get('/list/by_user/{id}', [PostController::class, 'get_list_by_user'])->middleware(['check_token','auth:api']);  
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'tag'

], function ($router) {
    Route::get('/list', [TagController::class, 'list'])->middleware(['check_token','auth:api']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'comment'

], function ($router) {
    Route::get('/list/{post_id}', [CommentController::class, 'get_comment_by_post_id'])->middleware(['check_token','auth:api']);   
    Route::post('/add', [CommentController::class, 'store'])->middleware(['check_token','auth:api']);   
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'user'

], function ($router) {
    Route::get('/posts', [UserController::class, 'listPost'])->middleware(['check_token','auth:api']);   
    Route::get('/info', [UserController::class, 'info'])->middleware(['check_token','auth:api']);
    Route::post('/update', [UserController::class, 'update']);
    Route::post('/address', [UserController::class, 'address'])->middleware(['check_token','auth:api','cors']);;
    Route::get('/refs', [UserController::class, 'refs'])->middleware(['check_token','auth:api']);
    Route::get('/get_balance', [UserController::class, 'get_balance'])->middleware(['check_token','auth:api']);
    Route::post('/disconnect', [UserController::class, 'disconnect'])->middleware(['check_token','auth:api']);
    Route::get('/check_vip', [UserController::class, 'controller_check_vip'])->middleware(['check_token','auth:api']);
    Route::get('/total_spin', [UserController::class, 'total_spin'])->middleware(['check_token','auth:api']);
    Route::get('/spin', [UserController::class, 'spin'])->middleware(['check_token','auth:api']);
    Route::get('/list_spin', [UserController::class, 'list_spin'])->middleware(['check_token','auth:api']);
    Route::post('/earn_spin', [UserController::class, 'earn_spin'])->middleware(['check_token','auth:api']);
    Route::get('/spin_pool', [UserController::class, 'spin_pool'])->middleware(['check_token','auth:api']);


    Route::get('/follow/{id}', [UserController::class, 'follow'])->middleware(['check_token','auth:api']);
    Route::get('/unfollow/{id}', [UserController::class, 'unfollow'])->middleware(['check_token','auth:api']);
    Route::get('/followers', [UserController::class, 'followers'])->middleware(['check_token','auth:api']);
    Route::get('/following', [UserController::class, 'following'])->middleware(['check_token','auth:api']);


    Route::get('/other_info/{id}', [UserController::class, 'other_info'])->middleware(['check_token','auth:api']);
    Route::post('/donate', [UserController::class, 'donate'])->middleware(['check_token','auth:api']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'system'

], function ($router) {
    Route::get('/app_version', [SystemController::class, 'app_version']);   
    Route::get('/allow_function', [SystemController::class, 'allow_function']);   
    Route::get('/home_alert', [SystemController::class, 'home_alert']);  
    Route::get('/guest', [SystemController::class, 'guest_token']);   
    Route::get('/currency', [SystemController::class, 'currency']);   

});


Route::group([
    'middleware' => 'api',
    'prefix' => 'earn'

], function ($router) {
    Route::get('/list', [EarnController::class, 'list'])->middleware(['check_token','auth:api']);
    Route::get('/earn', [EarnController::class, 'earn'])->middleware(['check_token','auth:api']);
    Route::get('/earn_total', [EarnController::class, 'earn_total'])->middleware(['check_token','auth:api']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'task'

], function ($router) {
    Route::get('/list', [TaskController::class, 'list'])->middleware(['check_token','auth:api']);
    Route::post('/earn', [TaskController::class, 'earn'])->middleware(['check_token','auth:api']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'ads'

], function ($router) {
    Route::get('/limit', [AdsController::class, 'limit'])->middleware(['check_token','auth:api']);
    Route::post('/earn', [AdsController::class, 'earn'])->middleware(['check_token','auth:api']);
    Route::get('/check_show_ads', [AdsController::class, 'check_show_ads'])->middleware(['check_token','auth:api']);
});



Route::group([
    'middleware' => 'api',
    'prefix' => 'kyc'

], function ($router) {
    Route::post('/add', [KycController::class, 'store'])->middleware(['check_token','auth:api']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'cashback'

], function ($router) {
    Route::get('/list_banner/{type}', [CashbackController::class, 'list_banner'])->middleware(['check_token','auth:api']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'brand'

], function ($router) {
    Route::get('/list/{type}', [BrandController::class, 'list'])->middleware(['check_token','auth:api']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'airdrop'

], function ($router) {
    Route::get('/list', [AirdropController::class, 'list'])->middleware(['check_token','auth:api']);
    Route::post('/submit', [AirdropSubmitController::class, 'submit'])->middleware(['check_token','auth:api']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'product'

], function ($router) {
    Route::get('/list', [ProductController::class, 'list'])->middleware(['check_token','auth:api']);
    Route::get('/detail/{id}', [ProductController::class, 'detail'])->middleware(['check_token','auth:api']);
});