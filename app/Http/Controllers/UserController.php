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
        return $this->success(User::where([
            ['category', $request->category],
            ['year', $request->year],
//

        ])->get());

    }

    public function index()
    {

        $user = User::where('id', '!=', auth()->user()->id);
        return $this->success($user);
    }

    public function takePresence(PresenceRequest $request)
    {
        $user_ids = $request['user_id'];
        $subject_id = $request['subject_id'];

        foreach ($user_ids as $user_id) {
            $user = User::find($user_id);
            $subject = $user->subjects()->wherePivot('subject_id', $subject_id)->wherePivot('user_id', $user_id)->withPivot('presence')->get();
            $presence = $subject->first()->pivot->presence;
            $subject->first()->pivot->update(['presence' => $presence + 1]);

        }

        return $this->success();


    }
}
