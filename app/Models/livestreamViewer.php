<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class livestreamViewer extends Model
{
    protected $table = 'livestream_viewer';
    protected $guarded=[];
    use HasFactory;
}
