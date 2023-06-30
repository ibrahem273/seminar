<?php

namespace App\Http\Controllers;

use App\Mail\forgotPassword;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SanctumController extends Controller
{
    public function forgotPassword(Request $request)
    {
//        return 223;
//return $request->input('email');
        $user = ($query = User::query());
        $user = $user->where('email', $request->input('email'))->first();

        if ($user == null) {
            return new JsonResponse('asdasd', status: 404);
        }

//        $resetPasswordToken = str_pad('', 4, '2', STR_PAD_LEFT);
//;
        $resetPasswordToken = rand(100000, 900000);
//        $resetPasswordToken="ibrahem";
        if (!$user_pass_reset = PasswordResetToken::where('email', $user->email)->first()) {
            PasswordResetToken::create([
                'email' => $user->email,
                'token' => $resetPasswordToken
            ]);

        } else {
            $user_pass_reset->update([
                'email' => $user->email,
                'token' => $resetPasswordToken

            ]);

        }
        $ara = ['resetPasswordToken' => $resetPasswordToken];
        Mail::send('reset_password_notification', compact('ara'), function ($message) use ($user) {
            $message->to($user->email, 'Reset Password Notification')->subject('PasswordResetMessage');
        }
        );


        return new JsonResponse(['message' => 'A code has been sent to your email address']);
    }

    public function resetPassword(Request $request)
    {
        $attributes = $request->validate(['email' => 'required|email', 'password' => 'required', 'token' => 'required']);


        $user = User::where('email', $attributes['email'])->first();

        if (!$user) {
            return new JsonResponse(['message' => 'incorrect email'], 404);


        }
        $resetRequest = PasswordResetToken::where('email', $user->email)->first();

        if (!$resetRequest || $resetRequest->token != $request->token) {
            return new JsonResponse(['message' => 'an error has been occured'], status: 401);


        }
        $user->update([
            'password' => Hash::make($attributes['password'])
        ]);
        $user->save();
        $user->tokens()->delete();

        PasswordResetToken::where('email', $user->email)->delete();

        $token = $user->createToken('authToken')->plainTextToken;
        return new JsonResponse(['accessToken' => $token], status: 200);

    }


}
