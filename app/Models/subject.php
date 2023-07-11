<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $guarded = [];
protected $hidden=['created_at','updated_at'];
    public function subject_time_schedule(): HasMany
    {
        return $this->hasMany(SubjectTimeSchedule::class);
    }

    public function doctor(): BelongsToMany
    {
        return $this->BelongsToMany(User::class);
    }

}
