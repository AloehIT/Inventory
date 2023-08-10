<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opname extends Model
{
    use HasFactory;

    protected $table = 'tbl_opname';

    protected $fillable = ['id', 'id_opname', 'tgl_opname', 'total_qty', 'sequenc', 'status', 'keterangan', 'user_id'];

    protected $hidden = [];
}
