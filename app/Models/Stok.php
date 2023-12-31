<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'tbl_stok';

    protected $fillable = ['id', 'id_stok', 'id_transaksi', 'id_transaksi_detail', 'kode_transaksi', 'id_barang', 'kode_barang', 'nama_barang', 'keterangan', 'tanggal', 'qty', 'sts_inout'];

    protected $hidden = [];
}
