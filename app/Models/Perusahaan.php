<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'config_app';

    protected $fillable = ['name_config', 'value', 'setting'];

    protected $hidden = [];
}
