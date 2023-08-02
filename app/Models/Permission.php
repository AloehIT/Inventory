<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'model_has_permissions';

    protected $fillable = ['permission_id', 'role_id'];

    protected $hidden = [];
}
