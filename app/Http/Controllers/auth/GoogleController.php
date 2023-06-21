<?php
//
namespace App\Http\Controllers\auth;
//
use App\Http\Controllers\Controller;
//use App\Models\User;
//use Illuminate\Http\Request;
//use Laravel\Socialite\Facades\Socialite;
//
use App\Models\User;

class GoogleController extends Controller
{
    public function  ss()
{return get_class(new User());

//    $user=new \App\Models\User();
//    $user->sendEmailVerificationNotification();
//$user->notify(\Illuminate\Support\Facades\Mail::send());

}

}
//    public function  ss()
//    {
//    <script>
//
//
//    }
//}

    //    public function handleGoogleCallback()
//    {
//        try {
//            $user=Socialite::driver('google')->user();
//            $userExisted=User::where('oauth_id',$user->id)->where('oauth_type','google')->first();
//        }
//        catch (){}
//    }
//
//    public function handleGoogleRedirect()
//    {
//
//        return Socialite::driver('google')->redirect();
//
//    }
//}
