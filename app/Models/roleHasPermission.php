<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roleHasPermission extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'role_has_permissions';

    protected $fillable = ['permission_id', 'role_id'];

    protected $hidden = [];
}
