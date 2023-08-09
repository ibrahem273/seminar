<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Models\subject;
use App\Models\User;
use Illuminate\Http\Request;
use Nette\Utils\Type;

class subjectController extends Controller
{
    public function store(StoreSubjectRequest $request)
    {
        $data = $request->validated();
        $data = subject::create($data);
        return $data;
    }


    public function attach_subject(Request $request)
    {

        $user = User::find(auth()->user()->id);
//        $subjects = subject::where([
//            ['category', '=', auth()->user()->category],
//            ['year', '=', auth()->user()->year]
//
//        ])->get(['id', 'DR']);
//        foreach ($subjects as $subject) {
//            $user->doctors()->attach($subject['DR']);
//            $user->subjects()->attach($subject['id'], ['passed' => 0]);
//        }
//
//        $deputies = $community->users()
//            ->wherePivot('role', 'deputy') // or equivalent value
//            -
//>get();
        $user_ids = [2];
            $subject_id = 1;
        foreach ($user_ids as $user_id) {
            $subject = $user->subjects()->wherePivot('subject_id', $subject_id)->wherePivot('user_id', $user_id)->withPivot('presence')->get();
            $presence = $subject->first()->pivot->presence;
            $subject->first()->pivot->update(['presence' => $presence + 1]);

        }

        return $subject;
//        return $user->load('doctors');

    }

    public function index(Request $request)
    {

        $user = User::find(auth()->user()->id);
        return $this->success(data: $user->load('subjects', 'subjects.subject_time_schedule', 'doctors'));

    }


}
