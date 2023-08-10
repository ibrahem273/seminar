<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification extends Model
{protected $table='notifications';
    use HasFactory;

    protected $guarded = [];
    protected $hidden=['updated_at','created_at','id'];





}
