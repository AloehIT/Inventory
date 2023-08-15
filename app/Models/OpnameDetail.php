<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpnameDetail extends Model
{
    use HasFactory;

    protected $table = 'tbl_opname_detail';

    protected $fillable = ['id', 'id_opname_detail', 'id_opname', 'tgl_bm', 'id_barang', 'kode_barang', 'qty', 'current_qty', 'satuan', 'nama_barang', 'tanggal'];

    protected $hidden = [];
}
