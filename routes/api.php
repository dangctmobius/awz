<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SystemController;

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
    Route::post('/change-pass', [AuthController::class, 'changePassWord']);    
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'post'

], function ($router) {
    Route::get('/list', [PostController::class, 'index'])->middleware(['check_token','auth:api']);   
    Route::post('/add', [PostController::class, 'store'])->middleware(['check_token','auth:api']);   
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
    Route::post('/address', [UserController::class, 'address'])->middleware(['cors']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'system'

], function ($router) {
    Route::get('/app_version', [SystemController::class, 'app_version']);   
});

