<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKeluar extends Model
{
    use HasFactory;

    protected $table = 'detail_barang_keluar';

    protected $fillable = ['id', 'id_bk_detail', 'id_bk', 'tgl_bk', 'id_barang', 'kode_barang', 'qty', 'satuan', 'nama_barang', 'tanggal'];

    protected $hidden = [];
}
