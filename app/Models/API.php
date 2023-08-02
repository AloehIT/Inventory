<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'api_master';

    protected $fillable = ['api', 'setting', 'description', 'owner_id'];

    protected $hidden = [];
}
