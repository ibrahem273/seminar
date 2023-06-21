<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Models\subject;
use App\Models\User;
use Illuminate\Http\Request;

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

//        return auth()->user()->id;

        $user = User::find(auth()->user()->id);

        $user->subjects()->attach($request['subjects']);
        return $user->subjects()->get();

    }
    public function index(Request $request)
    {

//        return auth()->user()->id;

        $user = User::find(auth()->user()->id);

//        $user->subjects()->attach($request['subjects']);
        return $this->success(data: $user->subjects()->get(),);

    }



}
