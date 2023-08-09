<?php

namespace App\Observers;

use App\Models\lecture;
use App\Models\photo;

class lectureModelObserver
{
    //

    public function created(lecture $lecture)
    {
return "aads";
        photo::create([
            'photoable_type'=>'App\lecture',
            'photoable_id'=>$lecture->id,
            'path'=>$lecture->path
        ]);


    }

}
