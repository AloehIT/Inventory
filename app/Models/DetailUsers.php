<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUsers extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'detail_users';

    protected $fillable = ['id', 'nama_users', 'telpon', 'profile', 'deskripsi_users', 'alamat_users'];

    protected $hidden = [];
}
