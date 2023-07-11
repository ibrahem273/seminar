<?php

namespace App\Observers;

use App\Models\photo;
use App\Models\subject;
use App\Models\User;

class UserModelObserve
{
    /**
     * Handle the User "created" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function created(User $user)
    {
        //attach the subjects and the DR's
        if ($user->isDoctor == 0) {
            $subjects = subject::where([
                ['category', '=', $user->category],
                ['year', '=', $user->year]
            ])->get(['id', 'DR']);
            foreach ($subjects as $subject) {
                $user->doctors()->attach($subject['DR']);
                $user->subjects()->attach($subject['id'], ['passed' => 0]);
            }

        }
        if ($user->photo_path) {
            photo::create(
                [
                    'photoable_id' => $user->id,
                    'photoable_type' => 'App\User',
                    'title' => 'user',
                    'path' => $user->photo_path


                ]
            );
        }


    }


}
