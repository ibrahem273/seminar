<?php

namespace App\Http\Controllers\livestream;

use App\Http\Controllers\Controller;
use App\Http\Requests\livestream\storeLiveStreamRequset;
use App\Http\Resources\livestream\showLivestreamResourcee;
use App\Models\livestream;
use Illuminate\Http\Request;

class livestreamController extends Controller
{


    public function allLivestream()
    {

        return livestream::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return livestream::find($request['id'])->load('Viewer');

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     */
    public function store(storeLiveStreamRequset $request)
    {
        $data = $request->validated();
        $data['broadcaster_id'] = auth()->user()->id;
//        if (auth()->user()->isDoctor == 0)
//            return response()->json(['message' => 'you not allowed to make livestream'], 422);
        $livestream = livestream::create($data);

        $livestream->Viewer()->attach([2]);

        return $request;


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return  livestream::all();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
