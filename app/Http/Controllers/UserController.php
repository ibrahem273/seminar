<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresenceRequest;
use App\Http\Requests\usersCategoryRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{


    public function getUsersCategory(usersCategoryRequest $request): JsonResponse
    {
        return $this->success(User::where('category',$request->category)->get());


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

        return $subject;


    }
}
