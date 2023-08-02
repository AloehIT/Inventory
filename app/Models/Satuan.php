<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $table = 'satuans';

    protected $fillable = ['id', 'satuan', 'keterangan_satuan', 'user_id'];

    protected $hidden = [];
}
