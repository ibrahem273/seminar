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

        return $user->load('doctors');

    }

    public function index(Request $request)
    {
        ;
        $user = User::find(auth()->user()->id);
        return $this->success(data: $user->load('subjects', 'subjects.subject_time_schedule', 'doctors'));

    }


}
