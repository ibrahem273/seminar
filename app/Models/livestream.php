<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class livestream extends Model
{
    protected $table = 'livestream';
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class);
    }

    public function Viewer(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'livestream_viewer', 'livestream_id', 'viewer_id')->
        as('livestream_viewer');

    }
}
