<?php

namespace App\Http\Controllers;

use App\Models\image;
use  Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

public function success(mixed $data='',string $message='okay',int $statusCode=200):JsonResponse
{
    return response()->json([
       'data'=>$data,
       'success'=>true,
       'message'=>$message
    ],$statusCode);




}
    public function store_photo(Request $request)
    {$path='';
        $image = new Image;

        $image->title = $request->title;

        if ($request->hasFile('image')) {
            $path = '/storage/'.$request->file('image')->store($request->title);
            $image->url = $path;
            $image->save();
            return  $path;
        }
        return'';
    }

    public function error(string $message='okay',int $statusCode=400):JsonResponse

    {
        return response()->json([
            'data'=>null,
            'success'=>false,
            'message'=>$message
        ],$statusCode);




    }


}
