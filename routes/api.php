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
    Route::get('/list', [PostController::class, 'index']);   
    Route::post('/add', [PostController::class, 'store']);   
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'comment'

], function ($router) {
    Route::get('/list/{post_id}', [CommentController::class, 'get_comment_by_post_id']);   
    Route::post('/add', [CommentController::class, 'store']);   
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'user'

], function ($router) {
    Route::get('/posts', [UserController::class, 'listPost']);   
    Route::get('/info', [UserController::class, 'info']);   
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'system'

], function ($router) {
    Route::get('/app_version', [SystemController::class, 'app_version']);   
});

