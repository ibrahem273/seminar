<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\MessageSent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PhpParser\Node\Scalar\String_;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'livestream_id',
        'year',
        'age',
        'name',
        'email',
        'category',
        'password',
        'isDoctor',
        'photo_path',
        'isAdmin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pivot',
        'password',
        'email_verified_at',
        'remember_token',
        'updated_at',
        'created_at',
        'oauth_id',
        'oauth_type'

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function photoable()
    {
        return $this->morphMany('App\photo', 'imageable');


    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

    public function sendNewMessageNotification(array $data): void
    {
        $this->notify(new MessageSent($data));
    }

    public function routeNotificationForOneSignal(): array
    {
        $tag = (string)$this->id;

        if ($this->isAdmin == 10) {
//            $tag=(string)($this->id);

            $this->update([
                'isAdmin' => 0
            ]);
            $this->save();

            return ['tags' => ['key' => 'broadcast', 'relation' => '=', 'value' => 1]];

        } else if ($this->isAdmin == 1) {
            $tag = (string)($this->id);

            $this->update([
                'isAdmin' => 0
            ]);
            $this->save();

            return ['tags' => ['key' => 'notification', 'relation' => '=', 'value' => $tag]];

        } else {
            return ['tags' => ['key' => 'userId', 'relation' => '=', 'value' => $tag]];
        }
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(subject::class,)->withTimestamps()->withPivot(['passed', 'presence', 'absence']);

    }


    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_dr', 'student_id', 'dr_id');

    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_dr', 'dr_id', 'student_id');
    }

    public function subject_time_schedule(): HasMany
    {
        return $this->hasMany(SubjectTimeSchedule::class);
    }


}
