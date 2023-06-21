<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/cookie',function (){return view('welcome');});
//Route::get('/register',function (){return view('welcome');});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/webSockets', function () {
    event(new \App\Events\PlayGroundEvent());
return  null;
});
//Route::post('auth/google/callback',[\App\Http\Controllers\auth\GoogleController::class,'handleGoogleCallback']);
//Route::post('auth/google/redirect',[\App\Http\Controllers\auth\GoogleController::class,'handleGoogleRedirect']);


Route::get('/reset-password/{token}',function ($token){
    return $token;
} )->middleware(['guest:'.config('fortify.guard')])
    ->name('password.reset');

////    ->middleware(['guest:'.config('fortify.guard')])
////    ->name('password.update');
//Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//    ->middleware(['guest:'.config('fortify.guard')])
//    ->name('password.email');
