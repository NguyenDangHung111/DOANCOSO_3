<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//php artisan serve --host=192.168.1.47 --port=8000
// C:\Windows\System32\drivers\etc\hosts

/* GET */
Route::get('/load', [AccountController::class, 'load'])->name('load');
Route::get('/get_accounts', [AccountController::class, 'get_accounts'])->name('get_accounts');
Route::get('/google', [AccountController::class, 'to_Google'])->name('google');
Route::get('/callBack_to_google', [AccountController::class, 'callBack_to_Google'])->name('callBack_to_google');
Route::get('/facebook', [AccountController::class, 'to_Facebook'])->name('facebook');
Route::get('/callBack_to_facebook', [AccountController::class, 'callBack_to_Facebook'])->name('callBack_to_facebook');

/* POST */
Route::post('/create_account', [AccountController::class, 'create_account'])->name('create_account');
Route::post('/login', [AccountController::class, 'login'])->name('login');
Route::post('/checkEmail', [AccountController::class, 'checkEmail'])->name('checkEmail');
Route::post('/sendOtp', [AccountController::class, 'sendOtp'])->name('sendOtp');
Route::post('/checkOtp', [AccountController::class, 'checkOtp'])->name('checkOtp');
Route::post('/forgotPassword', [AccountController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/deleteAccount', [AccountController::class, 'deleteAccount'])->name('deleteAccount');