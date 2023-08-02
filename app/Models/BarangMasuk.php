<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuks';

    protected $fillable = ['id', 'id_bm', 'tgl_bm', 'total_qty', 'sequenc', 'deskripsi_barang_masuk', 'keterangan', 'user_id'];

    protected $hidden = [];
}
