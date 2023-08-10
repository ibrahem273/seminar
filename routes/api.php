<?php

use App\Http\Controllers\auth\GoogleController;
use App\Http\Controllers\livestream\livestreamController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
\Illuminate\Support\Facades\Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::post('find_subject_lectures',[\App\Http\Controllers\lectureController::class,'find']);
Route::post('find_subject_courses',[\App\Http\Controllers\courseController::class,'find']);
Route::resource('/courses',\App\Http\Controllers\courseController::class)->only('store');
Route::get('/test', [GoogleController::class, 'ss']);
Route::post('/forgot-password', [\App\Http\Controllers\SanctumController::class, 'forgotPassword']);
Route::post('/reset-Password', [\App\Http\Controllers\SanctumController::class, 'resetPassword']);
Route::post('/register', [\App\Http\Controllers\UserController::class,'register']);
Route::post('/sanctum/token', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return '404';
    }

//     $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        'data' => ['user' => $user,
            'token' => $user->createToken($request->device_name)->plainTextToken,
        ],
        'success' => true,
        'message' => 'ok'
    ], 200);
}
);
Route::post('find_user_notifications',[\App\Http\Controllers\NotificationController::class,'find']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/allUser', function (Request $request) {
    $user = User::all();

    return response()->json([
        'data' => ['user' => $user,
        ],
        'success' => true,
        'message' => 'ok'
    ], 200);

});
Route::resource('image', \App\Http\Controllers\ImageController::class);
Route::post('/send_notify',[\App\Http\Controllers\NotificationController::class,'store']);
Route::middleware('auth:sanctum')->get('user/revoke', function (Request $request) {
    $user = $request->user();
    $user->tokens()->delete();

    return response()->json([
        'data' => [
        ],
        'success' => true,
        'message' => 'user_deleted'
    ], 200);

});
//

Route::prefix('/doctor')->group(function (){
    Route::resource('lecture',\App\Http\Controllers\lectureController::class)->only('store');
});
Route::middleware('auth:sanctum')->group(function () {

//    Route::get('/get_students_category',);
    Route::apiResource('chat', \App\Http\Controllers\chatController::class)->only(['store', 'index', 'show']);
//   Route::get('/message',[\App\Http\Controllers\chatMessageController::class,'index']);
    Route::apiResource('message', \App\Http\Controllers\chatMessageController::class)->only(['store', 'index']);

    Route::apiResource('subject', \App\Http\Controllers\subjectController::class)->only('store', 'index');


//    Route::get('attach_subject', [\App\Http\Controllers\subjectController::class, 'attach_subject']);

    Route::post('/presence',[\App\Http\Controllers\UserController::class,'takePresence']);

    Route::resource('/livestream',livestreamController::class)->only('store')->middleware(['DoctorMiddleware']);
    Route::post('/get_users_category',[\App\Http\Controllers\UserController::class,'getUsersCategory'])->middleware(['DoctorMiddleware']);
    Route::resource('/livestream',livestreamController::class)->only('index');
    Route::get('all_livestream',function (){
        return \App\Models\livestream::all();
    });
});
Route::post('findUser',[\App\Http\Controllers\UserController::class,'findUser']);

Route::prefix('/livesStream')->group(function (){
    Route::post('/store_livestream_field',[\App\Http\Controllers\UserController::class,'storeLivestreamField']);
});
//Route::post('/livestream',[livestreamController::class,'store'])
//    ->middleware('auth:sanctum');

//Route::group(['middleware' => ['auth:sanctum', 'DoctorMiddleware']], function() {
//    Route::post('/livestream',[livestreamController::class,'store'])->middleware('DoctorMiddleware');
//
//    // uses 'auth' middleware plus all middleware from $middlewareGroups['web']
////    Route::resource('blog','BlogController'); //Make a CRUD controller
//});

//Route::resource('/livestream',\App\Http\Controllers\livestream\livestreamController::class)->only('store','index');
//Route::resource('/livestream_viewer', \App\Http\Controllers\livestream\livestreamViewerControllerc::class)->only('index');

