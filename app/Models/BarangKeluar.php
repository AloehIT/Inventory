<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluars';

    protected $fillable = ['id', 'id_bk', 'tgl_bk', 'total_qty', 'sequenc', 'keterangan', 'user_id'];

    protected $hidden = [];
}
