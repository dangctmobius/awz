<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/verify/{email}', [VerifyEmailCodeController::class, 'verify']);

Route::get('/demo', function(){
    return view(('mail_send'));
});


Route::get('/reset_task', function(){
    \DB::table('user_ptc_task')->truncate();
});
