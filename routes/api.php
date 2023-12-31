<?php

use App\Http\Controllers\auth\GoogleController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

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
\Illuminate\Support\Facades\Broadcast::routes(['middleware'=>['auth:sanctum']]);
Route::get('/test',[GoogleController::class,'ss']);
Route::post('/forgot-password', [\App\Http\Controllers\SanctumController::class, 'forgotPassword']);
Route::post('/reset-Password', [\App\Http\Controllers\SanctumController::class, 'resetPassword']);
Route::post('/register',function (Request $request)
{
    User::create(['email'=>$request['email'],'password'=>Hash::make($request['password'])
    ,'name'=>$request['name']
    ]);

});
Route::post('/sanctum/token', function (Request $request) {
//    $request->validate([
//        'email' => 'required|email',
//        'password' => 'required',
//            'device_name' => 'required',
//    ]);
//    return 1324;
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
         return '404';
    }

//     $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        'data'=>['user'=>$user,
            'token'=> $user->createToken($request->device_name)->plainTextToken,
        ],
        'success'=>true,
        'message'=>'ok'
    ],200);
}
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/allUser',function (Request $request){
    $user =User::all();

    return response()->json([
        'data'=>['user'=>$user,
        ],
        'success'=>true,
        'message'=>'ok'
    ],200);

});

Route::middleware('auth:sanctum')->get('user/revoke',function(Request $request){
   $user=$request->user();
   $user->tokens()->delete();

    return response()->json([
        'data'=>[
        ],
        'success'=>true,
        'message'=>'user_deleted'
    ],200);

});
//
Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource('chat',\App\Http\Controllers\chatController::class)->only(['store','index','show']);
//   Route::get('/message',[\App\Http\Controllers\chatMessageController::class,'index']);
    Route::apiResource('message',\App\Http\Controllers\chatMessageController::class)->only(['store','index']);

    Route::apiResource('subject', \App\Http\Controllers\subjectController::class)->only('store','index');


    Route::get('attach_subject',[\App\Http\Controllers\subjectController::class,'attach_subject']);


});

