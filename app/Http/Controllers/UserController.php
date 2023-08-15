<?php

namespace App\Http\Controllers;

use App\Http\Requests\find_user_request;
use App\Http\Requests\livestream\storeLiveStreamRequset;
use App\Http\Requests\PresenceRequest;
use App\Http\Requests\registerStoreRequest;
use App\Http\Requests\store_livestream_field;
use App\Http\Requests\usersCategoryRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class UserController extends Controller
{
    function register(registerStoreRequest $request)
    {


        $user = User::create(['email' => $request['email'], 'password' => Hash::make($request['password'])
            , 'isDoctor' => $request['isDoctor'], 'photo_path' => $request['photo_path']
            , 'name' => $request['name'], 'age' => $request['age'], 'category' => $request['category'], 'year' => $request['year']
        ]);
        return $this->success('');

    }

    public function storeLivestreamField(store_livestream_field $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->update(['livestream_id' => $request->livestream_id]);
        $user->save();
        return $user;
    }

    public function findUser(find_user_request $request)
    {
        $user = User::where('id', $request->id)->get()->load('subjects');
        return $user;

    }

    public function getUsersCategory(usersCategoryRequest $request): JsonResponse
    {
        $users = User::where([
            ['category', $request->category],
            ['year', $request->year],
        ])->get();
        $users = User::where([
            ['category', $request->category],
            ['year', $request->year],
        ])->get();

        foreach ($users as $user)
            foreach ($user->subjects as $subject) {
                $user['presence'] = $subject->pivot->presence;
                $user['absence'] =  $subject->pivot->absence;
            }
        return $this->success($users);

    }

    public function index()
    {

        $user = User::where('id', '!=', auth()->user()->id);
        return $this->success($user);
    }

    public function takePresence(PresenceRequest $request)
    {
        $request->validated();
        $user_ids = $request['user_id'];
        $subject_id = $request['subject_id'];
        $user_ids_absence = $request['user_id_absence'];
        foreach ($user_ids as $user_id) {
            $user = User::find($user_id);
            $subject = $user->subjects()->wherePivot('subject_id', $subject_id)->wherePivot('user_id', $user_id)->withPivot('presence')->get();
            $presence = $subject->first()->pivot->presence;
            $subject->first()->pivot->update(['presence' => $presence + 1]);

        }
        foreach ($user_ids_absence as $user_id_absence) {
            $user = User::find($user_id_absence);
            $subject = $user->subjects()->wherePivot('subject_id', $subject_id)->wherePivot('user_id', $user_id_absence)->withPivot('absence')->get();
            $absence = $subject->first()->pivot->absence;

            $subject->first()->pivot->update(['absence' => $absence + 1]);

        }
        return $this->success();


    }
 public function login(\Symfony\Component\HttpFoundation\Request $request)
 {
     return $request;
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
    public function update_profile_picture(\Symfony\Component\HttpFoundation\Request $request)
    {
           $photo_path= $this->store_photo($request);
            $user=User::find($request->user_id);
           $user->photo_path=$photo_path;
 $user->save();
 return $this->success($photo_path);
    }
}
