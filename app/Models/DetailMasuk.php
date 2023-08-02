<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMasuk extends Model
{
    use HasFactory;

    protected $table = 'detail_barang_masuk';

    protected $fillable = ['id', 'id_bm_detail', 'id_bm', 'tgl_bm', 'id_barang', 'kode_barang', 'qty', 'nama_barang', 'tanggal'];

    protected $hidden = [];
}
